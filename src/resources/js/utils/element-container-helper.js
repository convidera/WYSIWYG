export function iterateAllElementContainers(callback) {
    const containers = document.getElementsByClassName("WYSIWYG__container") || [];
    for(let i = 0; i < containers.length; i++) {
        callback(containers[i]);
    }
}
