import stopEvent from '../utils/event-broker';
import { save } from '../utils/save';

export default function elementContainerKeyListener(e) {
    // e:    KeyboardEvent
    // this: element container

    // check if is CMD (on mac) or CTRL (other) pressed
    const isCtrlOrMeta = window.navigator.platform.match('Mac') ? e.metaKey : e.ctrlKey;

    // save
    if (isCtrlOrMeta && e.code === 'KeyS') {
        if (e.shiftKey) {
            // CRTL+SHIFT+A or CMD+SHIFT+A  =>  save all
            // let global listener handle this
            return true;
        }

        // CRTL+A or CMD+A  =>  save this element
        save(this);
        return stopEvent(e);
    }
}
