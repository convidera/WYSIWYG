import notify from "./notification";

export const config = (function() {
    const url = '/api/WYSIWYG/config';
    const xmlHttp = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
    xmlHttp.open('GET', url, false); // false: synchronous
    xmlHttp.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    xmlHttp.send(null);
    if (xmlHttp.status === 200) {
        const config = JSON.parse(xmlHttp.responseText);
        window.wysiwyg = window.wysiwyg || {};
        window.wysiwyg.config = config;
        return config;
    }
    notify('error', `Config could not be loaded.<br/><br/>Status: ${xmlHttp.status}<br/>response text:<br/>xmlHttp.responseText`);
    return null;
})();
