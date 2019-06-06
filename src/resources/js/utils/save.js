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
    const elementType = container.dataset.elementType;
    const value = getValue(container);
    console.log(container);

    document.getElementsByTagName("BODY")[0].setAttribute('cursor-wait', true);
    container.dataset.preventBlurEvent = 'true';
    container.blur();
    update({ id: id, value: value }, elementType, (xmlHttp) => {
        const data = JSON.parse(xmlHttp.responseText);
        refresh(container, data)
        .then((value) => {
            notify('success', 'Changes saved successfully.');
            //container.blur();
        })
        .catch((reason) => {
            console.error(reason);
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
    iterateAllTextElementContainers((container) => {
        elements.push({ id: container.dataset.id, value: getValue(container) });
    });
    saveAllElements(elements, 'text');
    
    elements = [];
    iterateAllMediaElementContainers((container) => {
        elements.push({ id: container.dataset.id, value: getValue(container) });
    });
    saveAllElements(elements, 'media');
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
        case 'media/image':
        case 'media/video':
            return window.wysiwyg.storage.media[container.dataset.id];
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
        case 'media/image':
        //    return refreshImage(container, data.value);
            console.log('toDo');
            break;
        default:
            notify('error', `Unknown container mimeType '${container.dataset.mimeType}'`);
            return Promise.reject();
    }
}

function saveAllElements(elements, elementType) {
    document.activeElement.dataset.preventBlurEvent = 'true';
    document.activeElement.blur();
    update(elements, elementType, (xmlHttp) => {
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

function update(data, elementType, fnSuccess = null, fnError = null) {
    const baseUrl = '/api/WYSIWYG';
    const mimeType = 'application/json';
    const url = `${baseUrl}/${elementType}`;
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
    switch(elementType) {
        case 'text':
            xmlHttp.send(JSON.stringify(data));
            break;
        case 'media':
            let formData = new FormData();
            formData.append('file', data);
            xmlHttp.send(formData);
            break;
        default:
            const msg = `Unknown container elementType '${elementType}'`;
            notify('error', msg);
            throw new Error(msg);
    }
}
