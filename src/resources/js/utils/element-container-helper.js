/**
 * Iterate all content elements of each type
 * e.g. 'text/plain', 'text/markdown', 'media', ...
 * 
 * @param {function} callback function called on each element.
 */
export function iterateAllElementContainers(callback) {
    const containers = document.getElementsByClassName("WYSIWYG__container") || [];
    for(let i = 0; i < containers.length; i++) {
        callback(containers[i]);
    }
}

/**
 * Iterate all text elements
 * 'text/plain' and 'text/markdown'
 * 
 * @param {function} callback function called on each element.
 */
export function iterateAllTextElementContainers(callback) {
    const containers = document.getElementsByClassName("WYSIWYG__container-text") || [];
    for(let i = 0; i < containers.length; i++) {
        callback(containers[i]);
    }
}

/**
 * Iterate all plain text elements 'text/plain'
 * 
 * @param {function} callback function called on each element.
 */
export function iterateAllTextPlainElementContainers(callback) {
    const containers = document.getElementsByClassName("WYSIWYG__container-text-plain") || [];
    for(let i = 0; i < containers.length; i++) {
        callback(containers[i]);
    }
}

/**
 * Iterate all markdown elements 'text/markdown'
 * 
 * @param {function} callback function called on each element.
 */
export function iterateAllMarkdownElementContainers(callback) {
    const containers = document.getElementsByClassName("WYSIWYG__container-text-markdown") || [];
    for(let i = 0; i < containers.length; i++) {
        callback(containers[i]);
    }
}

/**
 * Iterate all media elements 'media'
 * 
 * @param {function} callback function called on each element.
 */
export function iterateAllMediaElementContainers(callback) {
    const containers = document.getElementsByClassName("WYSIWYG__container-media") || [];
    for(let i = 0; i < containers.length; i++) {
        callback(containers[i]);
    }
}

/**
 * Iterate all media elements 'media/image'
 * 
 * @param {function} callback function called on each element.
 */
export function iterateAllImageElementContainers(callback) {
    const containers = document.getElementsByClassName("WYSIWYG__container-media-image") || [];
    for(let i = 0; i < containers.length; i++) {
        callback(containers[i]);
    }
}
