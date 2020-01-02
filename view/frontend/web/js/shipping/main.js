define([
    'ko',
    'Magento_Ui/js/form/element/select'
], function(ko, component){
    return component.extend({
        warehouses: ko.observableArray(['test1', 'test2', 'test3'])
    })
})
