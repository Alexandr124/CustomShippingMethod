define([
    'jquery',
    'uiRegistry',
    'ko',
    'Magento_Checkout/js/view/shipping'
],function ($, registry, ko, component){

    registry.get(function(element, name){
        console.log(name)
    })

    var city = registry.get('checkout.steps.shipping-step.shippingAddress.custom-shipping-method-fields.novaposhta_city_custom_field');
    var np_warehouse = registry.get('checkout.steps.shipping-step.shippingAddress.custom-shipping-method-fields.novaposhta_warehouse_custom_field');

    var temp = registry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.city');

    city.value.subscribe(function(newcity){
        console.log(newcity);




        $.ajax({
            type: "POST",
            dataType: "json",
            url: "https://api.novaposhta.ua/v2.0/json/",
            data: JSON.stringify({
                modelName: "AddressGeneral",
                calledMethod: "getWarehouses",
                methodProperties: {
                    "CityName": newcity,
                    Limit: 555
                },
                apiKey: "24b06ad5b19dbf9166aa18605552f593"
            }),
            headers: {
                "Content-Type": "application/json"
            },
            success: function(response){
                console.log(response);

                var temp = [];

                for (let index = 0; index < response.data.length; ++index) {
                    temp.push({
                        'value': index,
                        'label': response.data[index]['DescriptionRu']
                    })
                }

                np_warehouse.setOptions(temp)
            },
        });





    });
    return component.extend({})
})