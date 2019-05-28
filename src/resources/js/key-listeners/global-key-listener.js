import stopEvent from '../utils/event-broker';
import { saveAll } from '../utils/save';
import { iterateAllElementContainers } from '../utils/element-container-helper';

const DELTA = 500;
let lastKeypressTime = 0;

/**
 * Global keyboard event listener.
 *
 * @param {object} e keyboard event
 *
 * @return {boolean} true if event should be further propagated; otherwise false
 */
export default function globalKeyListener(e) {
    // e:    KeyboardEvent
    // this: #document

    // check if is CMD (on mac) or CTRL (other) pressed
    const isCtrlOrMeta = window.navigator.platform.match('Mac') ? e.metaKey : e.ctrlKey;

    // save all
    if (isCtrlOrMeta && e.shiftKey && e.code === 'KeyS') {
        // CRTL+SHIFT+A or CMD+SHIFT+A  =>  save all
        saveAll();
        return stopEvent(e);
    }

    // display borders on dopple key detection
    if ([ 'ControlLeft', 'ControlRight', 'ShiftLeft', 'ShiftRight' ].includes(e.code)) {
        let thisKeypressTime = new Date();
        if ( (thisKeypressTime - lastKeypressTime) <= DELTA ) {
            startBorderAnimation();
            // optional - if we'd rather not detect a triple-press
            // as a second double-press, reset the timestamp
            thisKeypressTime = 0;
        }
        lastKeypressTime = thisKeypressTime;
        return stopEvent(e);
    }

    // toggle insert mode
    if (isCtrlOrMeta && e.code === 'KeyE') {
        // CRTL+E or CMD+E  =>  toggle insert mode
        insertMode();
        return stopEvent(e);
    }

    // toggle insert mode
    if (isCtrlOrMeta && e.code === 'KeyP') {
        // CRTL+E or CMD+E  =>  toggle insert mode
        togglePlaceholder();
        return stopEvent(e);
    }

    // insert mode
    if (e.code === 'KeyI') {
        // I  =>  enable insert mode
        insertMode(true);
        return true;
    }

    // normal mode
    if (e.code === 'Escape') {
        // ESC  =>  disable insert mode -> normal mode
        insertMode(false);
        return stopEvent(e);
    }
}


//------------------------------------------------------\\
//------------------  P R I V A T E  -------------------\\
//------------------------------------------------------\\

/**
 * insert: true   => enable
 * insert: false  => disable
 * insert: null   => toggle
**/
function insertMode(insert = null) {
    iterateAllElementContainers((container) => {
        container.contentEditable = (insert === null) ? (container.contentEditable !== 'true') : insert;
    });
}

function togglePlaceholder() {
    iterateAllElementContainers((container) => {
        container.classList.toggle("no-placeholder");
    });
}

function startBorderAnimation() {
    iterateAllElementContainers((container) => {
        container.classList.add("WYSIWYG__container-border");
        setTimeout(function() {
            this.classList.remove("WYSIWYG__container-border");
        }.bind(container), 2000);
    });
}
