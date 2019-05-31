import elementContainerKeyListener from './key-listeners/element-container-key-listener';
import globalKeyListener from './key-listeners/global-key-listener';
import {
    iterateAllElementContainers,
    iterateAllMarkdownElementContainers
} from './utils/element-container-helper';
import stopEvent from './utils/event-broker';
import addEventListeners from './markdown/event-listener';

window.addEventListener("load", function() {
    // remove all to prevend submits/buttons, links and effects
    const fn_onclick = (e) => { return stopEvent(e); };
    const elements = document.getElementsByTagName("*");
    for (let i = 0; i < elements.length; i++) {
        elements[i].onclick = fn_onclick;
    }

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
