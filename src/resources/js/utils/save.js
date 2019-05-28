import { iterateAllElementContainers } from './element-container-helper';
import notify from './notification';

/**
 * Save specific element container.
 * @param {object} container html element of element container
 */
export function save(container) {
    const id = container.dataset.id;
    const value = container.innerText;

    update({ id: id, value: value }, id, (xmlHttp) => {
        const data = JSON.parse(xmlHttp.responseText);
        container.innerText = data.value;
        container.dataset.valueSaved = data.value;
        container.blur();
        notify('success', 'Changes saved successfully.');
    });
}

/**
 * Save all element container.
 */
export function saveAll() {
    elements = [];
    iterateAllElementContainers((container) => {
        elements.push({ id: container.dataset.id, value: container.innerText });
    });

    update(elements, null, (xmlHttp) => {
        let dataset = JSON.parse(xmlHttp.responseText);
        iterateAllElementContainers((container) => {
            let i = 0;
            for ( ; i < dataset.length; i++) {
                const data = dataset[i];
                if (data.id === container.dataset.id) {
                    container.innerText = data.value;
                    container.dataset.valueSaved = data.value;
                    //dataset.splice(i);  // | speed up, but is it possible that
                    //break;              // | one element is multiple time on one page?
                }
            }
            dataset.splice(i);
        });
        document.activeElement.blur();
        notify('success', 'All changes saved successfully.');
    });
}


//------------------------------------------------------\\
//------------------  P R I V A T E  -------------------\\
//------------------------------------------------------\\

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
