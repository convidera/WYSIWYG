import { iterateAllElementContainers } from './element-container-helper';
import notify from './notification';

import refreshText from '../text/refresh';
import refreshMarkdown from '../markdown/refresh';


/**
 * Save specific element container.
 * @param {object} container html element of element container
 */
export function save(container) {
    const id = container.dataset.id;
    const value = container.innerText;

    document.getElementsByTagName("BODY")[0].setAttribute('cursor-wait', true);
    update({ id: id, value: value }, id, (xmlHttp) => {
        const data = JSON.parse(xmlHttp.responseText);
        refresh(container, data)
        .then((value) => {
            notify('success', 'Changes saved successfully.');
            container.blur();
        })
        .catch((reason) => {
            console.log(reason);
            if (! reason || reason.xmlHttp) return;
            const xmlHttp = reason.xmlHttp;
            notify('error', `Oh no. Request failed. Status: ${xmlHttp.status}\n\nResponse:\n${xmlHttp.responseText}`);
        })
        .finally(() => {
            document.getElementsByTagName("BODY")[0].removeAttribute('cursor-wait');
        });
    });
}

/**
 * Save all element container.
 */
export function saveAll() {
    document.getElementsByTagName("BODY")[0].setAttribute('cursor-wait', true);

    let elements = [];
    iterateAllElementContainers((container) => {
        elements.push({ id: container.dataset.id, value: getValue(container) });
    });

    update(elements, null, (xmlHttp) => {
        let dataset = JSON.parse(xmlHttp.responseText);
        let promises = [];
        iterateAllElementContainers((container) => {
            let i = 0;
            for ( ; i < dataset.length; i++) {
                const data = dataset[i];
                if (data.id === container.dataset.id) {
                    promises.push(refresh(container, data));
                    //dataset.splice(i);  // | speed up, but is it possible that
                    //break;              // | one element is multiple time on one page?
                }
            }
            dataset.splice(i);
        });
        Promise.all(promises)
        .then((value) => {
            notify('success', 'All changes saved successfully.');
            document.activeElement.blur();
        })
        .catch((reason) => {
            console.log(reason);
            if (! reason || reason.xmlHttp) return;
            const xmlHttp = reason.xmlHttp;
            notify('error', `Oh no. Request failed. Status: ${xmlHttp.status}\n\nResponse:\n${xmlHttp.responseText}`);
        })
        .finally(() => {
            document.getElementsByTagName("BODY")[0].removeAttribute('cursor-wait');
        });
    });
}


//------------------------------------------------------\\
//------------------  P R I V A T E  -------------------\\
//------------------------------------------------------\\

function getValue(container) {
    switch (container.dataset.mimeType) {
        case 'text/plain':
            return container.innerText;
        case 'text/markdown':
            return container.dataset.valueCurrent;
        default:
            const msg = `Unknown container mimeType '${container.dataset.mimeType}'`;
            notify('error', msg);
            throw new Error(msg);
    }
}

function refresh(container, data) {
    switch (container.dataset.mimeType) {
        case 'text/plain':
            return refreshText(container, data.value);
        case 'text/markdown':
            return refreshMarkdown(container, data.value);
        default:
            notify('error', `Unknown container mimeType '${container.dataset.mimeType}'`);
            return Promise.reject();
    }
}

function update(data, urlExtension = null, fnSuccess = null, fnError = null) {
    const baseUrl = '/api/WYSIWYG';
    const mimeType = 'application/json';
    const url = (urlExtension) ? `${baseUrl}/${urlExtension}` : baseUrl;
    const xmlHttp = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
    xmlHttp.open('PUT', url, true);
    xmlHttp.setRequestHeader('Content-Type', mimeType);
    xmlHttp.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    xmlHttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            if (this.status == 200) {
                if (fnSuccess) {
                    fnSuccess(this);
                }
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
