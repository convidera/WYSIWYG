/**
 * Save media on server.
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
    return {
        id: container.dataset.id,
        value: window.wysiwyg.storage.media[container.dataset.id]
    };
}

function m_save(data, id) {
    return new Promise((resolve, reject) => {
        const baseUrl = '/api/WYSIWYG/media';
        const url = id ? `${baseUrl}/${id}` : baseUrl;

        const xmlHttp = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
        xmlHttp.open('POST', url, true);
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

        let formData = new FormData();
        formData.append('data', data);
        xmlHttp.send(formData);
    });
}
