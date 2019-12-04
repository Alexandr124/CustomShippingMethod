<?php

namespace Vaimo\NovaPoshta\Model\Plugin\Checkout;

class LayoutProcessor
{
    /**
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     */


    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array  $jsLayout
    ) {
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['delivery_date'] = [
            'component' => 'Magento_Ui/js/form/element/abstract',
            'config' => [
                'customScope' => 'shippingAddress',
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/date',
                'options' => [],
                'id' => 'delivery-date'
            ],
            'dataScope' => 'shippingAddress.delivery_date',
            'label' => 'Delivery Date',
            'provider' => 'checkoutProvider',
            'visible' => true,
            'validation' => [],
            'sortOrder' => 250,
            'id' => 'delivery-date'
        ];

        $opt_val = array();

        $opt_val['value']="value1";
        $opt_val['label'] = "Option1";
        $allOptions[] = $opt_val;

        $opt_val['value']="value2";
        $opt_val['label'] = "Option2";
        $allOptions[] = $opt_val;

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['drop_down'] = [
            'component' => 'Magento_Ui/js/form/element/select',
            'config' => [
                'customScope' => 'shippingAddress',
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/select',
                'id' => 'drop-down',
            ],
            'dataScope' => 'shippingAddress.drop_down',
            'label' => 'Drop Down',
            'provider' => 'checkoutProvider',
            'visible' => true,
            'validation' => [],
            'sortOrder' => 251,
            'id' => 'drop-down',
            'options' =>  $allOptions,
//            'options' => [
//                [
//                    'value' => '',
//                    'label' => 'Please Select',
//                ],
//                [
//                    'value' => '1',
//                    'label' => 'First Option',
//                ]
//            ]
        ];


//            $opt_val['value']=$v['option_id'];
//            $opt_val['label'] = $v['value'];
//
//            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
//            ['shippingAddress']['children']['shipping-address-fieldset']['children'][$attributeCode] = [
//                'component' => 'Magento_Ui/js/form/element/'.$fieldAbstract.'',
//                'config' => [
//                    'customScope' => 'shippingAddress.custom_attributes',
//                    'template' => 'ui/form/field',
//                    'elementTmpl' => 'ui/form/element/'.$fieldInputType.'',
//                    'id' => $attributeCode
//                ],
//                'dataScope' => 'shippingAddress.custom_attributes.'.$attributeCode.'',
//                'label' => $frontEndLabel,
//                'provider' => 'checkoutProvider',
//                'visible' => true,
//                'validation' =>[$fieldFrontendClass ,
//                    'required-entry' => $fieldRequiredClass],
//                'sortOrder' => 250,
//                'options' =>  [$opt_val],
//                'id' => $attributeCode
//            ];


        return $jsLayout;
    }
}