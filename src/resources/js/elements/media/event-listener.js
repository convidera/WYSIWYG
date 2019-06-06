import stopEvent from '../../utils/event-broker';
import getStrategy from '../strategy';


export default function addEventListeners(container) {
    // drop feature
    if(window.FileReader) {
        container.addEventListener('dragenter', onDragenter, false);
        container.addEventListener('dragleave', onDragLeave, false);
        container.addEventListener('dragover',  onDragover,  false);
        container.addEventListener('drop',      onDrop,      false);
    }
    else {
        console.log('Your browser does not support the HTML5 FileReader. Drag&Drop features are not supported.');
    }
}


//------------------------------------------------------\\
//------------------  P R I V A T E  -------------------\\
//------------------------------------------------------\\

function enabled() {
    return window.wysiwyg.insertMode;
}

function onDragenter(e) {
    if (!enabled()) return true;

    // Tells the browser that we *can* drop on this target
    return stopEvent(e);
}

function onDragLeave(e) {
    if (!enabled()) return true;

}

function onDragover(e) {
    if (!enabled()) return true;

    // Tells the browser that we *can* drop on this target
    return stopEvent(e);
}

function onDrop(e) {
    if (!enabled()) return true;

    const container = this;
    e = e || window.event; // get window.event if e argument missing (in IE)   

    const file = e.dataTransfer.files[e.dataTransfer.files.length - 1];
    window.wysiwyg.storage.media[container.dataset.id] = file;

    let reader = new FileReader();
    // attach event handlers here...
    reader.addEventListener("load", (e) => {
        getStrategy(container.dataset.mimeType).refreshValue(container, reader.result);
    }, false);
    reader.readAsDataURL(file);

    return stopEvent(e); // stops the browser from redirecting off to the image etc.
}
