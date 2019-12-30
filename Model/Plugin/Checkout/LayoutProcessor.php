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

    public function process($result)
    {
        $result = $this->getShippingFormFields($result);
        $result = $this->getBillingFormFields($result);
        return $result;
    }

    public function getAdditionalFields($addressType = 'shipping')
    {
        if ($addressType == 'shipping') {
            return ['novaposhta_warehouse'];
        }
        return ['novaposhta_warehouse'];
    }

    public function getShippingFormFields($result)
    {
        if (isset($result['components']['checkout']['children']['steps']['children']
            ['shipping-step']['children']['shippingAddress']['children']
            ['shipping-address-fieldset'])
        ) {

            $shippingPostcodeFields = $this->getFields('shippingAddress.custom_attributes', 'shipping');

            $shippingFields = $result['components']['checkout']['children']['steps']['children']
            ['shipping-step']['children']['shippingAddress']['children']
            ['shipping-address-fieldset']['children'];

            if (isset($shippingFields['street'])) {
                unset($shippingFields['street']['children'][1]['validation']);
                unset($shippingFields['street']['children'][2]['validation']);
            }

            $shippingFields = array_replace_recursive($shippingFields, $shippingPostcodeFields);

            $result['components']['checkout']['children']['steps']['children']
            ['shipping-step']['children']['shippingAddress']['children']
            ['shipping-address-fieldset']['children'] = $shippingFields;

        }

        return $result;
    }

    public function getBillingFormFields($result)
    {
        if (isset($result['components']['checkout']['children']['steps']['children']
            ['billing-step']['children']['payment']['children']
            ['payments-list'])) {

            $paymentForms = $result['components']['checkout']['children']['steps']['children']
            ['billing-step']['children']['payment']['children']
            ['payments-list']['children'];

            foreach ($paymentForms as $paymentMethodForm => $paymentMethodValue) {

                $paymentMethodCode = str_replace('-form', '', $paymentMethodForm);

                if (!isset($result['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'][$paymentMethodCode . '-form'])) {
                    continue;
                }

                $billingFields = $result['components']['checkout']['children']['steps']['children']
                ['billing-step']['children']['payment']['children']
                ['payments-list']['children'][$paymentMethodCode . '-form']['children']['form-fields']['children'];

                $billingPostcodeFields = $this->getFields('billingAddress' . $paymentMethodCode . '.custom_attributes', 'billing');

                $billingFields = array_replace_recursive($billingFields, $billingPostcodeFields);

                $result['components']['checkout']['children']['steps']['children']
                ['billing-step']['children']['payment']['children']
                ['payments-list']['children'][$paymentMethodCode . '-form']['children']['form-fields']['children'] = $billingFields;
            }
        }

        return $result;
    }

    public function getFields($scope, $addressType)
    {
        $fields = [];
        foreach ($this->getAdditionalFields($addressType) as $field) {
            $fields[$field] = $this->getField($field, $scope);
        }
        return $fields;
    }

    public function getField($attributeCode, $scope)
    {
        $field = [
            'config' => [
                'customScope' => $scope,
            ],
            'dataScope' => $scope . '.' . $attributeCode,
        ];

        return $field;
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

            $opt_val['value'] = $amount[$i]["city_name"];
            $opt_val['label'] = $amount[$i]["city_name"];

            $allOptions[] = $opt_val;
        }



        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['novaposhta_warehouse'] = [
            'component' => 'Vaimo_NovaPoshta/js/shipping/warehouse',
            'config' => [
                'customScope' => 'shippingAddress.custom_attributes',
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/select',
                'id' => 'novaposhta_warehouse',
            ],
            'dataScope' => 'shippingAddress.custom_attributes.novaposhta_warehouse',
            'label' => __('Novaposhta Warehouse'),
            'provider' => 'checkoutProvider',
            'visible' => true,
            'validation' => [],
            'sortOrder' => 251,
            'id' => 'novaposhta_warehouse',
        ];

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['novaposhta_warehouse']
        ['config']['elementTmpl'] = "Vaimo_NovaPoshta/shipping/warehouse";



        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['novaposhta_cities'] = [
            'component' => 'Magento_Ui/js/form/element/select',
            'config' => [
                'customScope' => 'shippingAddress',
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/select',
                'id' => 'novaposhta_cities',
            ],
            'dataScope' => 'shippingAddress.novaposhta_cities',
            'label' => __('City NP'),
            'provider' => 'checkoutProvider',
            'visible' => true,
            'validation' => [],
            'sortOrder' => 251,
            'id' => 'novaposhta_cities',
            'options' =>  $allOptions
        ];

//        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
//        ['shippingAddress']['children']['shipping-address-fieldset']['children']['city'] = [
//            'component' => 'Magento_Ui/js/form/element/select',
//            'config' => [
//                'customScope' => 'shippingAddress',
//                'template' => 'ui/form/field',
//                'elementTmpl' => 'ui/form/element/input',
//                'id' => 'city',
//            ],
//            'dataScope' => 'shippingAddress.city',
//            'label' => 'City',
//            'provider' => 'checkoutProvider',
//            'visible' => true,
//            'validation' => [],
//            'sortOrder' => 24,
//            'id' => 'novaposhta_cities',
//            'options' =>  $allOptions
//        ];




        return $jsLayout;
    }
}