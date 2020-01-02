var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/view/shipping': {
                'Vaimo_NovaPoshta/js/view/shipping': true
            }
        }
    },
    "map": {
        "*": {
            "Magento_Checkout/js/model/shipping-save-processor/default" : "Vaimo_NovaPoshta/js/shipping-save-processor"
        }
    }
};
