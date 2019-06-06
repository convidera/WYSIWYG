/**
 * Save text on server.
 * 
 * @param {array<element>|element} data  element container or array of element containers to save
 * 
 * @return {Promise}
 */
export function save(data) {
    if (Array.isArray(data)) {
        return m_save(data.map(prepareTransferData), null);
    }
    return m_save(prepareTransferData(data), data.dataset.id);
}


//------------------------------------------------------\\
//------------------  P R I V A T E  -------------------\\
//------------------------------------------------------\\

function prepareTransferData(container) {
    return { id: container.dataset.id, value: container.innerText };
}

function m_save(data, id) {
    return new Promise((resolve, reject) => {
        const baseUrl = '/api/WYSIWYG/text';
        const url = id ? `${baseUrl}/${id}` : baseUrl;
        const mimeType = 'application/json';

        const xmlHttp = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
        xmlHttp.open('PUT', url, true);
        xmlHttp.setRequestHeader('Content-Type', mimeType);
        xmlHttp.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
        xmlHttp.onreadystatechange = function() {
            if (this.readyState == 4) {
                if (this.status == 200) {
                    resolve(this);
                }
                else {
                    reject(this);
                }
            }
        };
        xmlHttp.send(JSON.stringify(data));
    });
}
