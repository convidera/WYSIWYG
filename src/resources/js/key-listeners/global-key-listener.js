import stopEvent from '../utils/event-broker';
import { saveAll } from '../utils/save';
import {
    iterateAllElementContainers,
    iterateAllTextElementContainers
} from '../utils/element-container-helper';

const DELTA = 500;
let lastKeypressTime = 0;

/**
 * Global keyboard event listener.
 *
 * @param {object} e keyboard event
 *
 * @return {boolean} true if event should be further propagated; otherwise false
 */
export default function globalKeyListener(e) {
    // e:    KeyboardEvent
    // this: #document

    // check if is CMD (on mac) or CTRL (other) pressed
    const isCtrlOrMeta = window.navigator.platform.match('Mac') ? e.metaKey : e.ctrlKey;

    // save
    if (isCtrlOrMeta && e.code === 'KeyS') {
        if (e.shiftKey) {
            // save all
            // CRTL+SHIFT+S or CMD+SHIFT+S  =>  save all
            saveAll();
            return stopEvent(e);
        }

        // CRTL+S or CMD+S  =>  ask user to save all
        if (confirm('No element selected. Do you want to save all elements?')) {
            saveAll();
        }
        return stopEvent(e);
    }

    // display borders on dopple key detection
    if ([ 'ControlLeft', 'ControlRight', 'ShiftLeft', 'ShiftRight' ].includes(e.code)) {
        let thisKeypressTime = new Date();
        if ( (thisKeypressTime - lastKeypressTime) <= DELTA ) {
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

    // toggle placeholder mode
    if (isCtrlOrMeta && e.code === 'KeyP') {
        // CRTL+E or CMD+E  =>  toggle placeholder mode
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
        document.body.removeAttribute('cursor-wait');
        return stopEvent(e);
    }
}


//------------------------------------------------------\\
//------------------  P R I V A T E  -------------------\\
//------------------------------------------------------\\

/**
 * insert: true   => enable
 * insert: false  => disable
 * insert: null   => toggle
**/
const insertMode = (function() {
    let enableInsertModeFirstTime = false;

    function prevendAllInteractions() {
        // remove all to prevend submits/buttons, links and effects
        const fn_onclick = (e) => {
            if (window.wysiwyg.insertMode) {
                return stopEvent(e);
            }
            return true;
        };
        const elements = document.getElementsByTagName("*");
        for (let i = 0; i < elements.length; i++) {
            elements[i].onclick = fn_onclick;
        }
    }

    function setPointerEventStyle(element, value) {
        if (value) {
            element.style.pointerEvents = value;
            return;
        }
        element.style.removeProperty('pointer-events');
    }
    
    return function(insert = null) {
        insert = (insert === null) ? !window.wysiwyg.insertMode : insert;
        window.wysiwyg.insertMode = insert;

        if (! enableInsertModeFirstTime) {
            enableInsertModeFirstTime = true;
            prevendAllInteractions();
        }

        // (un)set pointer-events to get more drag&drop support
        setPointerEventStyle(document.body, (insert) ? 'none' : null);
        iterateAllElementContainers((container) => {
            setPointerEventStyle(container, (insert) ? 'auto' : null);
        });

        // (un)set contenteditable to text elements (e.g. plain and markdown)
        iterateAllTextElementContainers((container) => {
            container.contentEditable = insert;
        });
    };
})();

function togglePlaceholder() {
    iterateAllElementContainers((container) => {
        container.classList.toggle("no-placeholder");
    });
}

function startBorderAnimation() {
    iterateAllElementContainers((container) => {
        container.classList.add("WYSIWYG__container-border");
        if (getComputedStyle(container, null).display === 'inline') {
            container.classList.add("WYSIWYG__container-border-no-inline");
        }
        setTimeout(function() {
            this.classList.remove("WYSIWYG__container-border");
            this.classList.remove("WYSIWYG__container-border-no-inline");
        }.bind(container), 2000);
    });
}
