export function iterateAllElementContainers(callback) {
    const containers = document.getElementsByClassName("WYSIWYG__container") || [];
    for(let i = 0; i < containers.length; i++) {
        callback(containers[i]);
    }
}
export function iterateAllTextElementContainers(callback) {
    const containers = document.getElementsByClassName("WYSIWYG__container-text") || [];
    for(let i = 0; i < containers.length; i++) {
        callback(containers[i]);
    }
}
export function iterateAllMarkdownElementContainers(callback) {
    const containers = document.getElementsByClassName("WYSIWYG__container-markdown") || [];
    for(let i = 0; i < containers.length; i++) {
        callback(containers[i]);
    }
}