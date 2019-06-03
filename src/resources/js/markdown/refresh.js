/**
 * Refresh markdown-element container.
 * 
 * @param {html-element} container text-element container
 * @param {markdown-element} value markdown element value (text-element)
 */
export default function refresh(container, value) {
    return new Promise((resolve, reject) => {
        container.dataset.valueCurrent = value;
        parse(value, (xmlHttp) => {
            container.innerHTML = xmlHttp.responseText;
            resolve(createResponseObject(container, value, xmlHttp));
        }, (xmlHttp) => {
            reject(createResponseObject(container, value, xmlHttp));
        });
    });
}


//------------------------------------------------------\\
//------------------  P R I V A T E  -------------------\\
//------------------------------------------------------\\

function createResponseObject(container, data, xmlHttp) {
    return {
        container: container,
        data: data,
        xmlHttp: xmlHttp,
        mimeType: 'text/markdown'
    };
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
