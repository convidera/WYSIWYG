import notify from '../utils/notification';
import refresh from './refresh';


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

    document.getElementsByTagName("BODY")[0].setAttribute('cursor-wait', true);
    refresh(container, value)
    .then((value) => {
        container.blur();
    })
    .catch((reason) => {
        const xmlHttp = reason.xmlHttp;
        notify('error', `Oh no. Request failed. Status: ${xmlHttp.status}\n\nResponse:\n${xmlHttp.responseText}`);
    })
    .finally(() => {
        document.getElementsByTagName("BODY")[0].removeAttribute('cursor-wait');
    });
}
