/**
 * Stop all further event handling.
 *
 * @param {object} e event object
 *
 * @return {boolean} false to cancel further event handling (old style)
 */
export default function stopEvent(e) {
    // local event handling
    if (e) {
        e.preventDefault();
        e.stopPropagation();
    }
    // global event handling
    const globalEvent = window.event;
    if (globalEvent) {
        if (globalEvent.preventDefault) {
            globalEvent.preventDefault();
        }
        else {
            globalEvent.returnValue = false;
        }
    }
    // cancel further event handling
    return false;
}
