import elementContainerKeyListener from './key-listeners/element-container-key-listener';
import globalKeyListener from './key-listeners/global-key-listener';
import {
    iterateAllElementContainers,
    iterateAllMarkdownElementContainers
} from './utils/element-container-helper';
import stopEvent from './utils/event-broker';
import addEventListeners from './markdown/event-listener';

window.addEventListener("load", function() {
    // attach element container keyboard listener
    iterateAllElementContainers((container) => {
        container.onkeydown = elementContainerKeyListener;
    });

    // attach markdown element container event listener
    iterateAllMarkdownElementContainers((container) => {
        addEventListeners(container);
    });

    // attach global keyboard listener
    document.onkeydown = globalKeyListener;
});
