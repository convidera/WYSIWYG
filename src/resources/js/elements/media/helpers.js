/**
 * Save media on server.
 * 
 * @param {array<element>|element} data  element container or array of element containers to save
 * 
 * @return {Promise}
 */
export function save(data) {
    const preparedData = prepareTransferData(data);
    if (preparedData) {
        return m_save(
            prepareTransferData(data),
            Array.isArray(data) ? null : data.dataset.id
        );
    }

    // no changes
    return Promise.resolve();
}


//------------------------------------------------------\\
//------------------  P R I V A T E  -------------------\\
//------------------------------------------------------\\

function prepareTransferData(data) {
    if (Array.isArray(data)) {
        // save multiple
        let formData = new FormData();
        let elementCounter = 0;
        for (let i = 0; i < data.length; i++) {
            const id = data[i].dataset.id;
            const file = window.wysiwyg.storage.media[id];
            if (file) {
                formData.append('ids[]', id);
                formData.append('files[]', file);
                elementCounter++;
            }
        }
        if (elementCounter > 0) {
            return formData;
        }
        return null;
    }

    // save single
    const id = data.dataset.id;
    const file = window.wysiwyg.storage.media[id];
    if (file) {
        let formData = new FormData();
        formData.append('id', id);
        formData.append('file', file);
        return formData;
    }
    return null;
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
        xmlHttp.send(data);
    });
}
