var config = {
    map: {
        '*': {
            'Magento_Checkout/template/shipping-address/shipping-method-list.html': 'Learn_NovaPoshta/template/shipping-address/shipping-method-list.html',
            // 'Magento_Checkout/js/action/place-order':'Learn_NovaPoshta/js/action/place-order'
        }
    },
    config: {
        mixins: {
            "Magento_Checkout/js/view/shipping": {
                "Learn_NovaPoshta/js/view/shipping-mixin": true
            },
            'Magento_Checkout/js/action/place-order': {
                'Learn_NovaPoshta/js/action/place-order-mixin': true
            }
        }
    }

};