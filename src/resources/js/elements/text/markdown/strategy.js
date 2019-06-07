import { save as saveText } from '../helpers';
import { iterateAllMarkdownElementContainers } from '../../../utils/element-container-helper';


/**
 * Iterate all markdown-element container of strategy.
 * 
 * @param {function} callback function called on each element.
 */
export function iterateAllElementContainers(callback) {
    iterateAllMarkdownElementContainers(callback);
}


/**
 * Save markdown-element on server.
 * 
 * @param {array<element>|element} data  markdown-element or array of markdown-elements to save
 * 
 * @return {Promise}
 */
export function save(data) {
    return saveText(data);
}

/**
 * Save all markdown-elements from document.
 * 
 * @return {Promise}
 */
export function saveAll() {
    let elements = [];
    iterateAllElementContainers((container)=> {
        elements.push(container);
    });
    return save(elements);
}


/**
 * Refresh markdown-element (text/markdown) container.
 * 
 * @param {html-element} container  element container
 * @param {element}      data       text-element data (markdown-element)
 * 
 * @return {Promise}
 */
export function refresh(container, data) {
    return refreshValue(container, data.value);
}

/**
 * Refresh markdown-element (text/markdown) container value.
 * 
 * @param {html-element} container  element container
 * @param {element}      value      text-element data (markdown-element) value
 * 
 * @return {Promise}
 */
export function refreshValue(container, value) {
    return new Promise((resolve, reject) => {
        container.dataset.valueCurrent = value;
        parse(value, (xmlHttp) => {
            container.innerHTML = xmlHttp.responseText;
            resolve({ container: container, value: value });
        }, (xmlHttp) => {
            reject({
                container: container,
                value: value,
                error: {
                    obj: xmlHttp
                }
            });
        });
    });
}


//------------------------------------------------------\\
//------------------  P R I V A T E  -------------------\\
//------------------------------------------------------\\

function parse(markdownRaw, fnSuccess, fnError = null) {
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
                    notify('error', `Oh no. Request failed. Status: ${xmlHttp.status}<br/><br/>Response:<br/>${xmlHttp.responseText}`);
                }
            }
        }
    };
    xmlHttp.send(JSON.stringify({ data: markdownRaw }));
}
