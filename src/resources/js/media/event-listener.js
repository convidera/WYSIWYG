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

function onDragenter(e) {
    // Tells the browser that we *can* drop on this target
    return stopEvent(e);
}

function onDragLeave(e) {

}

function onDragover(e) {
    // Tells the browser that we *can* drop on this target
    return stopEvent(e);
}

function onDrop(e) {
    e = e || window.event; // get window.event if e argument missing (in IE)   

    const files = e.dataTransfer.files;
    this.dataset.files = files;
    for (let i = 0; i < files.length; i++) {
        let file = files[i];
        let reader = new FileReader();
            
        // attach event handlers here...
        reader.addEventListener("load", (e) => {
            this.src = reader.result;
        }, false);

        reader.readAsDataURL(file);
    }
    return stopEvent(e); // stops the browser from redirecting off to the image etc.
}
