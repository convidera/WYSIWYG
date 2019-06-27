import notify from './notification';
import getStrategy, { iterateAllStrategies } from '../elements/strategy';


/**
 * Save specific element container.
 * @param {object} container html element of element container
 */
export function save(container) {
    document.body.setAttribute('cursor-wait', true);

    container.dataset.preventBlurEvent = 'true';
    container.blur();
    
    const strategy = getStrategy(container.dataset.mimeType);
    strategy.save(container)
    .then((xmlHttp) => {
        if ( ! xmlHttp) {
            // no db update
            const value = container.dataset.valueCurrent;
            if (value) {
                return getStrategy(container.dataset.mimeType)
                    .refreshValue(container, value);
            }
            return Promise.resolve();
        }

        const data = JSON.parse(xmlHttp.responseText);
        return strategy.refresh(container, data);
    })
    .then(() => {
        // notify user
        notify('success', 'Changes saved successfully.');
        return Promise.resolve();
    })
    .catch(handleError)
    .finally(() => {
        document.body.removeAttribute('cursor-wait');
    });
}

/**
 * Save all element container.
 */
export function saveAll() {
    document.body.setAttribute('cursor-wait', true);

    let activeElement = null;
    if (document.activeElement.classList.contains('WYSIWYG__container')) {
        activeElement = document.activeElement;
        activeElement.dataset.preventBlurEvent = 'true';
        activeElement.blur();
    }
    
    let promises = [];
    iterateAllStrategies((name, strategy) => {
        promises.push(strategy.saveAll().then((xmlHttp) => {
            if ( ! xmlHttp) {
                // no db update
                if (activeElement) {
                    const value = activeElement.dataset.valueCurrent;
                    if (value) {
                        return getStrategy(activeElement.dataset.mimeType)
                            .refreshValue(activeElement, value);
                    }
                }
                return Promise.resolve();
            }

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
        // clear storage
        window.wysiwyg.storage.media = [];        

        // notify user
        notify('success', 'All changes saved successfully.');
        return Promise.resolve();
    })
    .catch(handleError)
    .finally(() => {
        document.body.removeAttribute('cursor-wait');
    });
}


//------------------------------------------------------\\
//------------------  P R I V A T E  -------------------\\
//------------------------------------------------------\\

function handleError(reason) {
    console.error(reason);
    if (!reason || !reason.error) {
        // unknown error (ugly display)
        notify('error', reason);
        return Promise.resolve();
    }
    if (typeof reason.error.obj.status !== 'undefined' && typeof reason.error.obj.status !== 'undefined') {
        const xmlHttp = reason.error.obj;
        notify('error', `Oh no. Request failed. Status: ${xmlHttp.status}<br/><br/>Response:<br/>${xmlHttp.responseText}`);
        return Promise.resolve();
    }
    if (typeof reason.error.msg !== 'undefined') {
        notify('error', reason.error.msg);
        return Promise.resolve();
    }
}
