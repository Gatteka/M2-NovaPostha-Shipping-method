define([
    'jquery',
    'uiComponent',
    'Magento_Ui/js/modal/confirm',
    'ko',
], function (
    $,
    Component,
    confirmation,
    ko
) {
    window.calculateDeliveryCost = {
        init: function () {
            self = this;

        },
        calculate: function (url) {
            console.log(url);
            $.ajax({
                url: url,
                dataType: 'json',
                type: 'get',
                global: false,
                contentType: 'application/json'
            }).done(function () {
                self.initConfirmPopup('Done','shipping cost calculated.');
            }).fail(function () {
                self.initConfirmPopup('Error','shipping calculation failed.');
            });
        },
        initConfirmPopup: function (title,result) {
            confirmation({
                title: $.mage.__(title),
                content: $.mage.__(result),
                actions: {},
                buttons: [{
                    text: $.mage.__('Close'),
                    class: 'action-secondary action-dismiss',
                    click: function (event) {
                        this.closeModal(event);
                    }
                }
                ]
            });
        }
    }
});