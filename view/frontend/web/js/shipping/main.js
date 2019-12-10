define([
    'jquery',
    'uiRegistry',
    'Magento_Checkout/js/view/shipping'
    ],function ($, registry, component){

        registry.get(function(element, name){
            console.log(name)
        })

        city = registry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.city');
    np_warehouse = registry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.novaposhta_warehouse');

        city.value.subscribe(function(newcity){
            console.log(newcity)


            $.ajax({
                type: "POST",
                dataType: "json",
                url: "https://api.novaposhta.ua/v2.0/json/",
                data: JSON.stringify({
                    modelName: "AddressGeneral",
                    calledMethod: "getWarehouses",
                    methodProperties: {
                        "CityRef": newcity,
                        Limit: 555
                    },
                    apiKey: "24b06ad5b19dbf9166aa18605552f593"
                }),
                headers: {
                    "Content-Type": "application/json"
                },
                xhrFields: { // Свойство 'xhrFields' устанавливает дополнительные поля в XMLHttpRequest. // Это можно использовать для установки свойства 'withCredentials'. // Установите значение «true», если вы хотите передать файлы cookie на сервер. // Если это включено, ваш сервер должен ответить заголовком // 'Access-Control-Allow-Credentials: true'.
                    withCredentials: false
                },
                success: function(response){
                    console.log(response);

                    var temp = []

                    for (index = 0; index < response.data.length; ++index) {
                        console.log(response.data[index].Description);
                         temp[index] = [response.data[index].Description];
                    }




                    np_warehouse.warehouses(temp)
                },
            });


        })
        return component.extend({})
    })

