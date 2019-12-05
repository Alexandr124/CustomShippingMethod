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

        global $opt_val;

        $amount = $this->repository->getCollection();

        for($i=0; $i<count($amount); $i++){

            $opt_val['value'] = $amount[$i]["city_id"];
            $opt_val['label'] = $amount[$i]["city_name"];
            $allOptions[] = $opt_val;
        }


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
            'options' =>  $allOptions
        ];




        return $jsLayout;
    }
}