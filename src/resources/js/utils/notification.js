import Noty from 'noty';

export default function notify(type, message) {
    // https://ned.im/noty/#/options
    new Noty({
        type: type,
        text: message,
        timeout: 4000,
        closeWith: [ 'button' ]
    }).show();
    console.info(type, message);
}
