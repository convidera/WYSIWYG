import notify from '../../../utils/notification';
import getStrategy from '../../strategy';


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

    const container = this;
    const value = this.innerText;

    if (container.dataset.preventBlurEvent === 'true') {
        // At least save changes.
        container.dataset.valueCurrent = value;

        container.dataset.preventBlurEvent = 'false';
        return;
    }

    blurInProgess = true;

    document.body.setAttribute('cursor-wait', true);
    getStrategy('text/markdown').refreshValue(container, value)
    .catch(handleError)
    .finally(() => {
        document.body.removeAttribute('cursor-wait');
        blurInProgess = false;
    });
}

function handleError(reason) {
    console.error(reason);
    if (!reason || !reason.error) {
        // unknown error (ugly display)
        notify('error', reason);
        return Promise.resolve();
    }
    if (typeof reason.error.obj.status !== 'undefined' && typeof reason.error.obj.status !== 'undefined') {
        const xmlHttp = reason.error.obj;
        notify('error', `Oh no. Request failed. Status: ${xmlHttp.status}<br/><br/>Response:<br/>${xmlHttp.responseText}`);
        return Promise.resolve();
    }
    if (typeof reason.error.msg !== 'undefined') {
        notify('error', reason.error.msg);
        return Promise.resolve();
    }
}
