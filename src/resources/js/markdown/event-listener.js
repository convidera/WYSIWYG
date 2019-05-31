import notify from '../utils/notification';


export default function addEventListeners(container) {
    container.onfocus = onfocus;
    container.onblur = onblur;
}


//------------------------------------------------------\\
//------------------  P R I V A T E  -------------------\\
//------------------------------------------------------\\

function onfocus(e) {
    // e:    FocusEvent
    // this: element container
    
    this.innerText = this.dataset.valueCurrent;
}

function onblur(e) {
    // e:    FocusEvent
    // this: element container

    const container = this;
    const value = this.innerText;

    container.dataset.valueCurrent = value;
    document.getElementsByTagName("BODY")[0].setAttribute('cursor-wait', true);
    parse(value, (xmlHttp) => {
        container.innerHTML = xmlHttp.responseText;
        document.getElementsByTagName("BODY")[0].removeAttribute('cursor-wait');
        container.blur();
    }, (xmlHttp) => {
        notify('error', `Oh no. Request failed. Status: ${xmlHttp.status}\n\nResponse:\n${xmlHttp.responseText}`);
        document.getElementsByTagName("BODY")[0].removeAttribute('cursor-wait');
    });
}

function parse(data, fnSuccess, fnError = null) {
    const mimeType = 'application/json';
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
    xmlHttp.send(JSON.stringify({ data: data }));
}