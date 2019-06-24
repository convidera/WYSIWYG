// /**
//  * Element strategy.
//  */
// interface element {
//
//     /**
//      * Iterate all element container of strategy.
//      * 
//      * @param {function} callback function called on each element.
//      */
//     iterateAllElementContainers(callback);
//
//     /**
//      * Save element on server.
//      * 
//      * @param {array<element>|element} data  element or array of elements to save
//      * 
//      * @return {Promise}
//      */
//     save(data);
//
//     /**
//      * Save all elements from document.
//      * 
//      * @return {Promise}
//      */
//     saveAll();
//
//     /**
//      * Refresh element container.
//      * 
//      * @param {html-element} container  element container
//      * @param {element}      data       element data (e.g.: markdown-element)
//      *
//      * @return {Promise<RefreshResponse>}
//      */
//     refresh(container, data);
//
//     /**
//      * Refresh element container value.
//      * 
//      * @param {html-element} container  element container
//      * @param {element}      value      element data (e.g.: markdown-element) value
//      *
//      * @return {Promise<RefreshResponse>}
//      */
//     refresh(container, value);
// }

// /**
//  * Save promise (resolve and reject) response.
//  */
// SaveResponse: XMLHttpRequest

// /**
//  * Refresh promise (resolve and reject) response.
//  */
// struct RefreshResponse {
//     container;
//     value;
//     error?: {
//         obj?;
//         msg?;
//     }
// }


import * as TextPlainStrategy from './text/plain/strategy';
import * as MarkdownStrategy from './text/markdown/strategy';
import * as ImageStrategy from './media/image/strategy';


const strategies = {
    'text/plain':     TextPlainStrategy,
    'text/markdown':  MarkdownStrategy,
    'media/image':    ImageStrategy
};


/**
 * Get Strategy object of element.
 * 
 * @param {string} name  mime type of element equals strategy name
 */
export default function getStrategy(name) {
    // return require(`${name}/strategy.js`);
    return strategies[name];
}

/**
 * Iterate all strategies.
 * 
 * @param {function} callback function called on each strategy
 */
export function iterateAllStrategies(callback) {
    for (let name in strategies) {
        callback(name, strategies[name]);
    }
}