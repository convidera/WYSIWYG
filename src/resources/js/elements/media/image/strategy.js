import { save as saveMedia } from '../helpers';
import { iterateAllImageElementContainers } from '../../../utils/element-container-helper';


/**
 * Iterate all image-element container of strategy.
 * 
 * @param {function} callback function called on each element.
 */
export function iterateAllElementContainers(callback) {
    iterateAllImageElementContainers(callback);
}


/**
 * Save image-element on server.
 * 
 * @param {array<element>|element} data  image-element container or array of image-element containers to save
 * 
 * @return {Promise}
 */
export function save(data) {
    return saveMedia(data);
}

/**
 * Save all image-elements from document.
 * 
 * @return {Promise}
 */
export function saveAll() {
    let elements = [];
    iterateAllElementContainers((container)=> {
        elements.push(container);
    });
    return save(elements);
}


/**
 * Refresh image-element (media/image) container.
 * 
 * @param {html-element} container  element container
 * @param {element}      data       image-element data (image-element)
 * 
 * @return {Promise}
 */
export function refresh(container, data) {
    return refreshValue(container, data.value);
}

/**
 * Refresh image-element (media/image) container value.
 * 
 * @param {html-element} container  element container
 * @param {element}      value      image-element data (image-element) value
 * 
 * @return {Promise}
 */
export function refreshValue(container, value) {
    return new Promise((resolve, reject) => {
        if (container.hasAttribute('src')) {
            container.src = value;
        }
        else {
            container.style.backgroundImage = `url('${value}')`;
        }
        resolve({ container: container, value: value });
    }); 
}
