window.addEventListener("load", function() {
    // remove all to prevend submits/buttons, links and effects
    const elements = document.getElementsByTagName("*");
    for (let i = 0; i < elements.length; i++) {
        elements[i].onclick = function(e) { return stopEvent(e); };
    }

    // attach text element container listener
    iterateAllTextElements((container) => {
        container.onkeydown = textElementKeyListener;
    });
});
document.onkeydown = globalKeyListener;


let delta = 500;
let lastKeypressTime = 0;
function globalKeyListener(e) {
    // e:    KeyboardEvent
    // this: #document

    // check if is CMD (on mac) or CTRL (other) pressed
    const isCtrlOrMeta = window.navigator.platform.match('Mac') ? e.metaKey : e.ctrlKey;

    // save all
    if (isCtrlOrMeta && e.shiftKey && e.code === 'KeyS') {
        // CRTL+SHIFT+A or CMD+SHIFT+A  =>  save all
        saveAll();
        return stopEvent(e);
    }

    // display borders on dopple key detection
    if ([ 'ControlLeft', 'ControlRight', 'ShiftLeft', 'ShiftRight' ].includes(e.code)) {
        let thisKeypressTime = new Date();
        if ( (thisKeypressTime - lastKeypressTime) <= delta ) {
            startBorderAnimation();
            // optional - if we'd rather not detect a triple-press
            // as a second double-press, reset the timestamp
            thisKeypressTime = 0;
        }
        lastKeypressTime = thisKeypressTime;
        return stopEvent(e);
    }

    // toggle insert mode
    if (isCtrlOrMeta && e.code === 'KeyE') {
        // CRTL+E or CMD+E  =>  toggle insert mode
        insertMode();
        return stopEvent(e);
    }

    // toggle insert mode
    if (isCtrlOrMeta && e.code === 'KeyP') {
        // CRTL+E or CMD+E  =>  toggle insert mode
        togglePlaceholder();
        return stopEvent(e);
    }

    // insert mode
    if (e.code === 'KeyI') {
        // I  =>  enable insert mode
        insertMode(true);
        return true;
    }

    // normal mode
    if (e.code === 'Escape') {
        // ESC  =>  disable insert mode -> normal mode
        insertMode(false);
        return stopEvent(e);
    }
}

/**
 * insert: true   => enable
 * insert: false  => disable
 * insert: null   => toggle
**/
function insertMode(insert = null) {
    iterateAllTextElements((container) => {
        container.contentEditable = (insert === null) ? (container.contentEditable !== 'true') : insert;
    });
}

function togglePlaceholder() {
    iterateAllTextElements((container) => {
        container.classList.toggle("no-placeholder");
    });
}

function startBorderAnimation() {
    iterateAllTextElements((container) => {
        container.classList.add("WYSIWYG__container-border");
        setTimeout(function() {
            this.classList.remove("WYSIWYG__container-border");
        }.bind(container), 2000);
    });
}

function textElementKeyListener(e) {
    // e:    KeyboardEvent
    // this: text element container

    // check if is CMD (on mac) or CTRL (other) pressed
    const isCtrlOrMeta = window.navigator.platform.match('Mac') ? e.metaKey : e.ctrlKey;

    // save
    if (isCtrlOrMeta && e.code === 'KeyS') {
        if (e.shiftKey) {
            // CRTL+SHIFT+A or CMD+SHIFT+A  =>  save all
            // let global listener handle this
            return true;
        }

        // CRTL+A or CMD+A  =>  save this text element
        save(this);
        return stopEvent(e);
    }
}

function save(container) {
    const id = container.dataset.id;
    const value = container.innerText;

    update({ id: id, value: value }, id, (xmlHttp) => {
        const data = JSON.parse(xmlHttp.responseText);
        container.innerText = data.value;
        container.dataset.valueSaved = data.value;
        container.blur();
        notify('success', 'Save', 'Text element saved.');
    });
}

function saveAll() {
    elements = [];
    iterateAllTextElements((container) => {
        elements.push({ id: container.dataset.id, value: container.innerText });
    });

    update(elements, null, (xmlHttp) => {
        let dataset = JSON.parse(xmlHttp.responseText);
        iterateAllTextElements((container) => {
            let i = 0
            for ( ; i < dataset.length; i++) {
                const data = dataset[i];
                if (data.id === container.dataset.id) {
                    container.innerText = data.value;
                    container.dataset.valueSaved = data.value;
                    //dataset.splice(i);  // | speed up, but is it possible that
                    //break;              // | one text element is multiple time on one page?
                }
            }
            dataset.splice(i);
        });
        document.activeElement.blur();
        notify('success', 'Save all', 'All text element saved.');
    });
}

function update(data, urlExtension = null, fnSuccess, fnError = null) {
    const baseUrl = '/api/WYSIWYG';
    const mimeType = 'application/json';
    const url = (urlExtension) ? `${baseUrl}/${urlExtension}` : baseUrl;
    const xmlHttp = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
    xmlHttp.open('PUT', url, true);
    xmlHttp.setRequestHeader('Content-Type', mimeType);
    xmlHttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            if (this.status == 200) {
                fnSuccess(this);
            }
            else {
                if (fnError) {
                    fnError(this);
                }
                else {
                    notify('error', `Oh no. Request failed. Status: ${xmlHttp.status}\n\nResponse:\n${xmlHttp.responseText}`);
                }
            }
        }
    };
    xmlHttp.send(JSON.stringify(data));
}

function stopEvent(e) {
    // local event handling
    if (e) {
        e.preventDefault();
        e.stopPropagation();
    }
    // global event handling
    const globalEvent = window.event;
    if (globalEvent) {
        globalEvent.preventDefault ? globalEvent.preventDefault() : (globalEvent.returnValue = false);
    }
    // cancel further event handling
    return false;
}

function iterateAllTextElements(callback) {
    const containers = document.getElementsByClassName("WYSIWYG__container") || [];
    for(let i = 0; i < containers.length; i++) {
        callback(containers[i]);
    }
}

function notify(type, title, message) {
    /*window.createNotification({
        closeOnClick: true,
        displayCloseButton: false,
        positionClass: 'nfc-top-right',
        showDuration: 3000,
        theme: type
    })({
        title: title,
        message: message
    });*/
}

// https://www.cssscript.com/minimal-notification-popup-pure-javascript/
// Demo: https://www.cssscript.com/demo/minimal-notification-popup-pure-javascript/
// License: MIT
// Stand: 24.05.2019
//!function(t){function n(i){if(e[i])return e[i].exports;var o=e[i]={i:i,l:!1,exports:{}};return t[i].call(o.exports,o,o.exports,n),o.l=!0,o.exports}var e={};n.m=t,n.c=e,n.d=function(t,e,i){n.o(t,e)||Object.defineProperty(t,e,{configurable:!1,enumerable:!0,get:i})},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,n){return Object.prototype.hasOwnProperty.call(t,n)},n.p="",n(n.s=0)}([function(t,n,e){e(1),t.exports=e(4)},function(t,n,e){"use strict";var i=Object.assign||function(t){for(var n=1;n<arguments.length;n++){var e=arguments[n];for(var i in e)Object.prototype.hasOwnProperty.call(e,i)&&(t[i]=e[i])}return t};e(2);var o=e(3);!function(t){function n(t){return t=i({},c,t),function(t){return["nfc-top-left","nfc-top-right","nfc-bottom-left","nfc-bottom-right"].indexOf(t)>-1}(t.positionClass)||(console.warn("An invalid notification position class has been specified."),t.positionClass=c.positionClass),t.onclick&&"function"!=typeof t.onclick&&(console.warn("Notification on click must be a function."),t.onclick=c.onclick),"number"!=typeof t.showDuration&&(t.showDuration=c.showDuration),(0,o.isString)(t.theme)&&0!==t.theme.length||(console.warn("Notification theme must be a string with length"),t.theme=c.theme),t}function e(t){return t=n(t),function(){var n=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{},e=n.title,i=n.message,c=r(t.positionClass);if(!e&&!i)return console.warn("Notification must contain a title or a message!");var a=(0,o.createElement)("div","ncf",t.theme);if(!0===t.closeOnClick&&a.addEventListener("click",function(){return c.removeChild(a)}),t.onclick&&a.addEventListener("click",function(n){return t.onclick(n)}),t.displayCloseButton){var s=(0,o.createElement)("button");s.innerText="X",!1===t.closeOnClick&&s.addEventListener("click",function(){return c.removeChild(a)}),(0,o.append)(a,s)}if((0,o.isString)(e)&&e.length&&(0,o.append)(a,(0,o.createParagraph)("ncf-title")(e)),(0,o.isString)(i)&&i.length&&(0,o.append)(a,(0,o.createParagraph)("nfc-message")(i)),(0,o.append)(c,a),t.showDuration&&t.showDuration>0){var l=setTimeout(function(){c.removeChild(a),0===c.querySelectorAll(".ncf").length&&document.body.removeChild(c)},t.showDuration);(t.closeOnClick||t.displayCloseButton)&&a.addEventListener("click",function(){return clearTimeout(l)})}}}function r(t){var n=document.querySelector("."+t);return n||(n=(0,o.createElement)("div","ncf-container",t),(0,o.append)(document.body,n)),n}var c={closeOnClick:!0,displayCloseButton:!1,positionClass:"nfc-top-right",onclick:!1,showDuration:3500,theme:"success"};t.createNotification?console.warn("Window already contains a create notification function. Have you included the script twice?"):t.createNotification=e}(window)},function(t,n,e){"use strict";!function(){function t(t){this.el=t;for(var n=t.className.replace(/^\s+|\s+$/g,"").split(/\s+/),i=0;i<n.length;i++)e.call(this,n[i])}if(!(void 0===window.Element||"classList"in document.documentElement)){var n=Array.prototype,e=n.push,i=n.splice,o=n.join;t.prototype={add:function(t){this.contains(t)||(e.call(this,t),this.el.className=this.toString())},contains:function(t){return-1!=this.el.className.indexOf(t)},item:function(t){return this[t]||null},remove:function(t){if(this.contains(t)){for(var n=0;n<this.length&&this[n]!=t;n++);i.call(this,n,1),this.el.className=this.toString()}},toString:function(){return o.call(this," ")},toggle:function(t){return this.contains(t)?this.remove(t):this.add(t),this.contains(t)}},window.DOMTokenList=t,function(t,n,e){Object.defineProperty?Object.defineProperty(t,n,{get:e}):t.__defineGetter__(n,e)}(Element.prototype,"classList",function(){return new t(this)})}}()},function(t,n,e){"use strict";Object.defineProperty(n,"__esModule",{value:!0});var i=n.partial=function(t){for(var n=arguments.length,e=Array(n>1?n-1:0),i=1;i<n;i++)e[i-1]=arguments[i];return function(){for(var n=arguments.length,i=Array(n),o=0;o<n;o++)i[o]=arguments[o];return t.apply(void 0,e.concat(i))}},o=(n.append=function(t){for(var n=arguments.length,e=Array(n>1?n-1:0),i=1;i<n;i++)e[i-1]=arguments[i];return e.forEach(function(n){return t.appendChild(n)})},n.isString=function(t){return"string"==typeof t},n.createElement=function(t){for(var n=arguments.length,e=Array(n>1?n-1:0),i=1;i<n;i++)e[i-1]=arguments[i];var o=document.createElement(t);return e.length&&e.forEach(function(t){return o.classList.add(t)}),o}),r=function(t,n){return t.innerText=n,t},c=function(t){for(var n=arguments.length,e=Array(n>1?n-1:0),c=1;c<n;c++)e[c-1]=arguments[c];return i(r,o.apply(void 0,[t].concat(e)))};n.createParagraph=function(){for(var t=arguments.length,n=Array(t),e=0;e<t;e++)n[e]=arguments[e];return c.apply(void 0,["p"].concat(n))}},function(t,n){}]);
