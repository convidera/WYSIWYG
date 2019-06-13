import elementContainerKeyListener from './key-listeners/element-container-key-listener';
import globalKeyListener from './key-listeners/global-key-listener';
import {
    iterateAllElementContainers,
    iterateAllMarkdownElementContainers,
    iterateAllMediaElementContainers
} from './utils/element-container-helper';
import addMarkdownEventListeners from './elements/text/markdown/event-listener';
import addMediaEventListeners from './elements/media/event-listener';
import stopEvent from './utils/event-broker';

window.addEventListener("load", function() {
    // initialize storage
    window.wysiwyg = window.wysiwyg || {};
    window.wysiwyg.storage = window.wysiwyg.storage || {};
    window.wysiwyg.storage.media = [];

    // attach element container keyboard listener
    iterateAllElementContainers((container) => {
        container.onkeydown = elementContainerKeyListener;
    });

    // attach markdown element container event listener
    iterateAllMarkdownElementContainers((container) => {
        addMarkdownEventListeners(container);
    });

    // attach media element container event listener
    iterateAllMediaElementContainers((container) => {
        addMediaEventListeners(container); 
    });

    // attach global keyboard listener
    document.onkeydown = globalKeyListener;

    // prevent all drap and drop events globally
    const preventDropEvent = (e) => {
        e.dataTransfer.effectAllowed = 'none';
        e.dataTransfer.dropEffect = 'none';
        return stopEvent(e);
    };
    document.getElementsByTagName("BODY")[0].addEventListener('dragover', preventDropEvent, false);
    document.getElementsByTagName("BODY")[0].addEventListener('drop',     preventDropEvent, false);

    // prototypes
    // only implement if no native implementation is available
    if (typeof Array.isArray === 'undefined') {
        Array.isArray = function(obj) {
            return Object.prototype.toString.call(obj) === '[object Array]';
        };
    }
});
