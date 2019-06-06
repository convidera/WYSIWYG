import { iterateAllElementContainers } from './element-container-helper';
import notify from './notification';
import getStrategy, { iterateAllStrategies } from '../elements/strategy';


/**
 * Save specific element container.
 * @param {object} container html element of element container
 */
export function save(container) {
    document.getElementsByTagName("BODY")[0].setAttribute('cursor-wait', true);
    
    const strategy = getStrategy(container.dataset.mimeType);
    strategy.save(container)
    .then((xmlHttp) => {
        const data = JSON.parse(xmlHttp.responseText);
        return strategy.refresh(container, data);
    })
    .catch(handleError)
    .finally(() => {
        document.getElementsByTagName("BODY")[0].removeAttribute('cursor-wait');
    });

    container.dataset.preventBlurEvent = 'true';
    container.blur();
}

/**
 * Save all element container.
 */
export function saveAll() {
    document.getElementsByTagName("BODY")[0].setAttribute('cursor-wait', true);
    
    let promises = [];
    iterateAllStrategies((name, strategy) => {
        promises.push(strategy.saveAll().then((xmlHttp) => {
            let dataset = JSON.parse(xmlHttp.responseText);
            let refreshPromises = [];
            
            strategy.iterateAllElementContainers((container) => {
                let i = 0;
                for ( ; i < dataset.length; i++) {
                    const data = dataset[i];
                    if (data.id === container.dataset.id) {
                        refreshPromises.push(strategy.refresh(container, data));
                        //dataset.splice(i);  // | speed up, but is it possible that
                        //break;              // | one element is multiple time on one page?
                    }
                }
                dataset.splice(i);
            });

            return Promise.all(refreshPromises);
        }));
    });

    Promise.all(promises)
    .then(() => {
        notify('success', 'All changes saved successfully.');
        return Promise.resolve();
    })
    .catch(handleError)
    .finally(() => {
        document.getElementsByTagName("BODY")[0].removeAttribute('cursor-wait');
    });

    if (document.activeElement.classList.contains('WYSIWYG__container')) {
        document.activeElement.dataset.preventBlurEvent = 'true';
        document.activeElement.blur();
    }
}


//------------------------------------------------------\\
//------------------  P R I V A T E  -------------------\\
//------------------------------------------------------\\

function handleError(reason) {
    console.error(reason);
    if (!reason || !reason.error) return Promise.resolve();
    if (typeof reason.error.obj.status !== 'undefined' && typeof reason.error.obj.status !== 'undefined') {
        const xmlHttp = reason.error.obj;
        notify('error', `Oh no. Request failed. Status: ${xmlHttp.status}\n\nResponse:\n${xmlHttp.responseText}`);
        return Promise.resolve();
    }
    if (typeof reason.error.msg !== 'undefined') {
        notify('error', reason.error.msg);
        return Promise.resolve();
    }
}
