import notify from '../utils/notification';
import refresh from './refresh';


export default function addEventListeners(container) {
    container.onfocus = onfocus;
    container.onblur = onblur;
}


//------------------------------------------------------\\
//------------------  P R I V A T E  -------------------\\
//------------------------------------------------------\\

let blurInProgess = false;

function onfocus(e, container = null) {
    // e:    FocusEvent
    // this: element container

    container = container || this;

    if (blurInProgess) {
        // Problem: Between blur need some time to parse
        //   and change the content (innerText/innerHTML).
        //   If you focus before the blur is finish you
        //   will change the markdown html not markdown code.
        setTimeout(() => { onfocus(e, container); }, 100);
    }
    else {
        container.innerText = container.dataset.valueCurrent;
    }
}

function onblur(e) {
    // e:    FocusEvent
    // this: element container

    if (this.dataset.preventBlurEvent === 'true') {
        this.dataset.preventBlurEvent = 'false';
        return;
    }

    blurInProgess = true;

    const container = this;
    const value = this.innerText;

    document.getElementsByTagName("BODY")[0].setAttribute('cursor-wait', true);
    refresh(container, value)
    .then((value) => {
    })
    .catch((reason) => {
        const xmlHttp = reason.xmlHttp;
        notify('error', `Oh no. Request failed. Status: ${xmlHttp.status}\n\nResponse:\n${xmlHttp.responseText}`);
    })
    .finally(() => {
        document.getElementsByTagName("BODY")[0].removeAttribute('cursor-wait');
        blurInProgess = false;
    });
    return;
}
