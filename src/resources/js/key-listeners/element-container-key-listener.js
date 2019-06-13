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
            // CRTL+SHIFT+S or CMD+SHIFT+S  =>  save all
            // let global listener handle this
            return true;
        }

        // CRTL+S or CMD+S  =>  save this element
        save(this);
        return stopEvent(e);
    }
}
