import { save as saveText } from '../helpers';
import { iterateAllTextPlainElementContainers } from '../../../utils/element-container-helper';


/**
 * Iterate all markdown-element container of strategy.
 * 
 * @param {function} callback function called on each element.
 */
export function iterateAllElementContainers(callback) {
    iterateAllTextPlainElementContainers(callback);
}


/**
 * Save text-element on server.
 * 
 * @param {array<element>|element} data  text-element container or array of text-element containers to save
 * 
 * @return {Promise}
 */
export function save(data) {
    return saveText(data);
}

/**
 * Save all text-elements from document.
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
 * Refresh text-element (text/plain) container.
 * 
 * @param {html-element} container  element container
 * @param {element}      data       text-element data (text-element)
 * 
 * @return {Promise}
 */
export function refresh(container, data) {
    return refreshValue(container, data.value);
}

/**
 * Refresh text-element (text/plain) container value.
 * 
 * @param {html-element} container  element container
 * @param {element}      value      text-element data (text-element) value
 * 
 * @return {Promise}
 */
export function refreshValue(container, value) {
    return new Promise((resolve, reject) => {
        container.innerText = value;
        container.dataset.valueSaved = value;
        resolve({ container: container, value: value });
    });
}
