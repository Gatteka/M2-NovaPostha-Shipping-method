define([
    'jquery',
    'ko',
    'Magento_Checkout/js/model/quote',
], function ($, ko, quote) {
    'use strict';
    let citiesArray = [];
    let novaPoshtaConfig = window.checkoutConfig.novaPoshtaConfig;
    let cities = novaPoshtaConfig.cities;
    let warehouses = novaPoshtaConfig.warehouses;

    for (let id in cities) {
        citiesArray.push({
            'city_id': cities[id]['city_id'],
            'city_name': cities[id]['city_name']
        });
    }

    var mixin = {
        citySelected: ko.observable(false),
        dataRetrieved: ko.observable(!!novaPoshtaConfig),
        cities: ko.observable(citiesArray),
        warehouses: ko.observable(warehouses),
        warehousesCount: ko.observable(0),
        npCarrier: ko.observable(false),

        defaults: {
            template: 'Magento_Checkout/shipping',
            shippingFormTemplate: 'Magento_Checkout/shipping-address/form',
            shippingMethodListTemplate: 'Magento_Checkout/shipping-address/shipping-method-list'
        },

        initialize: function () {
            self = this;
            self.carrier_code = "nova_poshta_shipping";
            this._super();
        },

        changeSelectedCity: function (element, event) {
            let cityId = $(event.target).prop('selected', true).val().pop();
            cityId ? self.citySelected(cityId) && self.warehousesCount(self.warehouses()[self.citySelected()].length)
                : self.citySelected(false);
            quote['np_destination_city'] = event.target.selectedOptions[0].innerText;
            quote['np_destination_city_id'] = $(event.target).prop('selected', true).val().pop();
        },

        changeSelectedWarehouse: function (element, event) {
            quote['np_destination_warehouse'] = event.target.selectedOptions[0].innerText;
            quote['np_destination_warehouse_id'] = $(event.target).prop('selected', true).val().pop();
        },

        isSelected: ko.computed(function () {
            if (quote.shippingMethod()) {
                (quote.shippingMethod()['carrier_code'] === self.carrier_code && self.dataRetrieved()) ?
                    self.npCarrier(true) : self.npCarrier(false) && self.citySelected(false);
            }

            return quote.shippingMethod() ?
                quote.shippingMethod()['carrier_code'] + '_' + quote.shippingMethod()['method_code'] :
                null;
        })
    };

    return function (target) {
        return target.extend(mixin);
    }
});