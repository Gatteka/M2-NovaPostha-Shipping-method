define([
    'jquery',
    'uiComponent',
    'ko',
], function (
    $,
    Component,
    ko
) {
    return Component.extend({
        defaults: {
            template: 'Learn_NovaPoshta/button',
        },
        initialize: function () {
            this._super();
            return this;
        },
        calculate: function () {
            console.log('test');
            // $.ajax({
            //     url: "",
            //     dataType: 'json',
            //     type: 'get',
            //     global: false,
            //     contentType: 'application/json'
            // }).done(function () {
            //     console.log('done');
            //    // customerData.reload('messages');
            // }).fail(function (jqXhr) {
            //     console.log('error');
            //     // let errorResponse = $.parseJSON(jqXhr.responseText);
            //     // customerData.set('messages', {
            //     //     messages: [{
            //     //         type: 'error',
            //     //         text: errorResponse.message
            //     //     }]
            //     // });
            // });
        },
    });
});