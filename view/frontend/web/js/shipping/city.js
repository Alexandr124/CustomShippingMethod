define([
    'ko',
    'Magento_Ui/js/form/element/select'
], function(ko, component){
    return component.extend({
        citiy_names: ko._value("default")
    })
})