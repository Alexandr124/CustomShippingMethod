<?php

namespace Vaimo\NovaPoshta\Model\Plugin\Checkout;


use Vaimo\NovaPoshta\Model\CityRepository;

class LayoutProcessor
{
    /**
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     */
    public $repository;

    public function __construct(
        CityRepository $repository
    ) {
     $this->repository = $repository;
    }


    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array  $jsLayout
    ) {


        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['component']='Vaimo_NovaPoshta/js/shipping/main';

        global $opt_val;

        $amount = $this->repository->getCollection();

        for($i=0; $i<count($amount); $i++){

            $opt_val['value'] = $amount[$i]["ref"];
            $opt_val['label'] = $amount[$i]["city_name"];
            $allOptions[] = $opt_val;
        }

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
        ['children']['shippingAddress']['children']['shipping-address-fieldset']
        ['children']['city'] = [
            'component' => 'Magento_Ui/js/form/element/select',
            'config' => [
                'customScope' => 'shippingAddress.city',
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/select',
                'id' => 'city',
            ],
            'dataScope' => 'shippingAddress',
            'label' => 'City',
            'provider' => 'checkoutProvider',
            'visible' => true,
            'validation' => [],
            'sortOrder' => 251,
            'id' => 'city',
            'options' =>  $allOptions
        ];


        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['novaposhta_warehouse'] = [
            'component' => 'Vaimo_NovaPoshta/js/shipping/warehouse',
            'config' => [
                'customScope' => 'shippingAddress',
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/select',
                'id' => 'drop-down',
            ],
            'dataScope' => 'shippingAddress.novaposhta_warehouse',
            'label' => 'Warehouse',
            'provider' => 'checkoutProvider',
            'visible' => true,
            'validation' => [],
            'sortOrder' => 251,
            'id' => 'novaposhta_warehouse',
        ];

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['novaposhta_warehouse']
        ['config']['elementTmpl'] = "Vaimo_NovaPoshta/shipping/warehouse";

//        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
//        ['shippingAddress']['children']['shipping-address-fieldset']['children']['novaposhta_cities'] = [
//            'component' => 'Magento_Ui/js/form/element/select',
//            'config' => [
//                'customScope' => 'shippingAddress',
//                'template' => 'ui/form/field',
//                'elementTmpl' => 'ui/form/element/select',
//                'id' => 'novaposhta_cities',
//            ],
//            'dataScope' => 'shippingAddress.novaposhta_cities',
//            'label' => 'City',
//            'provider' => 'checkoutProvider',
//            'visible' => true,
//            'validation' => [],
//            'sortOrder' => 251,
//            'id' => 'novaposhta_cities',
//            'options' =>  $allOptions
//        ];


        return $jsLayout;
    }
}