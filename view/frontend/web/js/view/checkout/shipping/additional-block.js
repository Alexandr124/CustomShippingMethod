define([
    'jquery',
    'ko',
    'uiComponent',
    'Magento_Checkout/js/model/quote'
], function ($, ko, Component, quote) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Vaimo_NovaPoshta/checkout/shipping/additional-block'
        },

      //need to check radiobutton conditin, if checked -> show template

    });
});
