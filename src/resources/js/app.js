import elementContainerKeyListener from './key-listeners/element-container-key-listener';
import globalKeyListener from './key-listeners/global-key-listener';
import {
    iterateAllElementContainers,
    iterateAllMarkdownElementContainers,
    iterateAllMediaElementContainers
} from './utils/element-container-helper';
import addMarkdownEventListeners from './markdown/event-listener';
import addMediaEventListeners from './media/event-listener';

window.addEventListener("load", function() {
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
    // let el = document.createElement("IMG");
    // document.getElementsByTagName("BODY")[0].prepend(el);
    // el.style.cssText = "min-height: 50px; min-width: 50px;"; 
    // addMediaEventListeners(el);

    // attach global keyboard listener
    document.onkeydown = globalKeyListener;
});
