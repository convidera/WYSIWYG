//import notify from '../utils/notification';
//import refresh from './refresh';
import stopEvent from '../utils/event-broker';


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
    return !window.wysiwyg.insertMode;
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

    e = e || window.event; // get window.event if e argument missing (in IE)   

    const file = e.dataTransfer.files[e.dataTransfer.files.length - 1];
    window.wysiwyg.storage.media[this.dataset.id] = file;

    let reader = new FileReader();
    // attach event handlers here...
    reader.addEventListener("load", (e) => {
        this.style.backgroundImage = `url('${reader.result}')`;
        //this.src = reader.result;
    }, false);
    reader.readAsDataURL(file);

    return stopEvent(e); // stops the browser from redirecting off to the image etc.
}
