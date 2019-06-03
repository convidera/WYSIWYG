/**
 * Refresh text-element container.
 * 
 * @param {html-element} container text-element container
 * @param {text-element} value text element value
 */
export default function refresh(container, value) {
    return new Promise((resolve, reject) => {
        container.innerText = value;
        container.dataset.valueSaved = value;
        resolve(createResponseObject(container, value));
    });
}


//------------------------------------------------------\\
//------------------  P R I V A T E  -------------------\\
//------------------------------------------------------\\

function createResponseObject(container, data) {
    return {
        container: container,
        data: data,
        mimeType: 'text/plain'
    };
}
