var Clipboard = require('./../vendor/clipboard/clipboard.js');

$(function () {
    var clipboard = new Clipboard('[data-clipboard-text]')
    clipboard.on('success', function (e) {
        window.api.dialog.tipSuccess('ε€εΆζε')
    })
})

window.api.clipboard = Clipboard