define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote',
], function ($, wrapper,quote) {
    'use strict';

    return function (placeOrderAction) {
        return wrapper.wrap(placeOrderAction, function (originalAction, paymentData, messageContainer) {

            var billingAddress = quote.billingAddress();

            if(billingAddress !== undefined) {
                if (billingAddress['extension_attributes'] === undefined) {
                    billingAddress['extension_attributes'] = {};
                }

                billingAddress['extension_attributes']['np_destination_data'] = {
                   // np_destination_city : quote.np_destination_city,
                    city_id : quote.np_destination_city_id,
                  //  np_destination_warehouse: quote.np_destination_warehouse,
                    warehouse_id: quote.np_destination_warehouse_id
            };
            }

            return originalAction(paymentData, messageContainer);
        });
    };
});


