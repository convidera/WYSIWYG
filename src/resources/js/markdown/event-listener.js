export default function addEventListeners(container) {
    container.onclick = onclick;
    container.onblur = onblur;
}


//------------------------------------------------------\\
//------------------  P R I V A T E  -------------------\\
//------------------------------------------------------\\

function onclick(e) {
    // e:    MouseEvent
    // this: element container
    
    this.innerText = container.dataset.valueCurrent;
}

function onblur(e) {
    // e:    FocusEvent
    // this: element container

    const container = this;
    const value = this.innerText;

    container.dataset.valueCurrent = value;
    parse(value, (xmlHttp) => {
        container.innerHtml = xmlHttp.responseText;
        container.blur();
    });
}

function parse(data, fnSuccess, fnError = null) {
    const mimeType = 'text/plain';
    const url = '/api/WYSIWYG/markdown-parser';
    const xmlHttp = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
    xmlHttp.open('POST', url, true);
    xmlHttp.setRequestHeader('Content-Type', mimeType);
    xmlHttp.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
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
    xmlHttp.send(data);
}