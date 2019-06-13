import stopEvent from '../../utils/event-broker';
import getStrategy from '../strategy';
import { config } from '../../utils/config';
import notify from '../../utils/notification';


export default function addEventListeners(container) {
    // drop feature
    if(window.FileReader) {
        container.addEventListener('dragenter', onDragenter, false);
        container.addEventListener('dragleave', onDragLeave, false);
        container.addEventListener('dragover',  onDragover,  false);
        container.addEventListener('drop',      onDrop,      false);
    }
    else {
        const msg = 'Your browser does not support the HTML5 FileReader. Drag&Drop features are not supported.';
        console.log(msg);
        notify('warning', msg);
    }
}


//------------------------------------------------------\\
//------------------  P R I V A T E  -------------------\\
//------------------------------------------------------\\

function enabled(e) {
    if (window.wysiwyg.insertMode) {
        e.dataTransfer.effectAllowed = 'copy';
        e.dataTransfer.dropEffect = 'copy';
        return true;
    }

    e.dataTransfer.effectAllowed = 'none';
    e.dataTransfer.dropEffect = 'none';
    return false;
}

function onDragenter(e) {
    if (!enabled(e)) return true;

    // Tells the browser that we *can* drop on this target
    return stopEvent(e);
}

function onDragLeave(e) {
    if (!enabled(e)) return true;

    e.dataTransfer.effectAllowed = 'none';
    e.dataTransfer.dropEffect = 'none';
}

function onDragover(e) {
    if (!enabled(e)) return true;

    // Tells the browser that we *can* drop on this target
    return stopEvent(e);
}

function onDrop(e) {
    if (!enabled(e)) return true;

    const container = this;
    e = e || window.event; // get window.event if e argument missing (in IE)   

    try {
        const file = e.dataTransfer.files[e.dataTransfer.files.length - 1];
        if (fileRequirementsFulfilled(file, container.dataset.mimeType)) {
            window.wysiwyg.storage.media[container.dataset.id] = file;

            let reader = new FileReader();
            reader.addEventListener("load", (e) => {
                getStrategy(container.dataset.mimeType).refreshValue(container, reader.result);
            }, false);
            reader.readAsDataURL(file);
        }
    } finally{
        return stopEvent(e); // stops the browser from redirecting off to the image etc.
    }
}

function fileRequirementsFulfilled(file, mimeType) {
    let size = file.size;
    let unit = 0;
    while (size >= 1024) {
        size /= 1024;
        unit++;
    }
    size = Math.round(size * 100) / 100;  // round to 2 digits

    let sizeConfig = null;
    switch (mimeType) {
        case 'media/image':
            sizeConfig = config.imageMaxSize;
            break;
        case 'media/video':
            sizeConfig = config.videoMaxSize;
            break;
        default:
            console.error(`Unhandeled mime type: '${mimeType}'`);
            return false;
    }

    if (unit > sizeConfig.unit || (unit == sizeConfig.unit && size > sizeConfig.size)) {
        const extensions = [ 'Bytes', 'KB', 'MB', 'GB' ];
        notify(
            'error',
            `The media file is to big.<br/>File is ${size} ${extensions[unit]} large but max size is ${sizeConfig.size} ${extensions[sizeConfig.unit]}`
        );
        return false;
    }
    return true;
}
