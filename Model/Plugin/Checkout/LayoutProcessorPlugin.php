<?php
/**
* *
*  @author DCKAP Team
*  @copyright Copyright (c) 2018 DCKAP (https://www.dckap.com)
*  @package Dckap_CustomFields
*/

namespace Vaimo\NovaPoshta\Model\Plugin\Checkout;

use Magento\Checkout\Block\Checkout\LayoutProcessor;
use Vaimo\NovaPoshta\Model\CityRepository;
use Vaimo\NovaPoshta\Model\WarehouseRepository;

/** Adding 2 new custom fields City & Warehouse. (Shown when custom shipping method is selected).
* Class LayoutProcessorPlugin
* @package Vaimo\NovaPoshta\Model\Plugin\Checkout
*/
class LayoutProcessorPlugin
{
    /**
     * @var WarehouseRepository
     */
    protected $warehouseRepository;

    /**
     * LayoutProcessorPlugin constructor.
     * @param CityRepository $repository
     * @param WarehouseRepository $warehouseRepository
     */
    public function __construct(
        CityRepository $repository,
        WarehouseRepository $warehouseRepository
    ) {
        $this->repository = $repository;
        $this->warehouseRepository = $warehouseRepository;
    }

   /**
    * @param LayoutProcessor $subject
    * @param array $jsLayout
    * @return array
    */
   public function afterProcess(
       LayoutProcessor $subject,
       array $jsLayout
   ) {

       $validation['required-entry'] = true;

       $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
       ['shippingAddress']['children']['custom-shipping-method-fields']['children']['novaposhta_city_custom_field'] = [
           'component' => "Magento_Ui/js/form/element/select",
           'config' => [
               'customScope' => 'customShippingMethodFields',
               'template' => 'ui/form/field',
               'elementTmpl' => "ui/form/element/select",
               'id' => "novaposhta_city_custom_field"
           ],
           'dataScope' => 'customShippingMethodFields.custom_shipping_field[novaposhta_city_custom_field]',
           'label' => "City NP",
           'options' => $this->getCities(),
           'caption' => 'Please select city',
           'provider' => 'checkoutProvider',
           'visible' => true,
           'validation' => $validation,
           'sortOrder' => 2,
           'id' => 'custom_shipping_field[novaposhta_city_custom_field]'
       ];


       $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
       ['shippingAddress']['children']['custom-shipping-method-fields']['children']['novaposhta_warehouse_custom_field'] = [
           'component' => "Magento_Ui/js/form/element/select",
           'config' => [
               'customScope' => 'customShippingMethodFields',
               'template' => 'ui/form/field',
               'elementTmpl' => "ui/form/element/select",
               'id' => "novaposhta_warehouse_custom_field"
           ],
           'dataScope' => 'customShippingMethodFields.custom_shipping_field[novaposhta_warehouse_custom_field]',
           'label' => "NovaPoshta Warehouse",
           'options' => $this->getWarehouses(),
           'provider' => 'checkoutProvider',
           'visible' => true,
           'validation' => [],
           'sortOrder' => 4,
           'id' => 'custom_shipping_field[novaposhta_warehouse_custom_field]'
       ];

//       $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
//       ['shippingAddress']['children']['custom-shipping-method-fields']['children']['novaposhta_warehouse_custom_field']
//       ['config']['elementTmpl'] = "Vaimo_NovaPoshta/shipping/warehouse";


       return $jsLayout;
   }


    /**
     * @return array
     */
    protected function getCities()
    {
       $amount = $this->repository->getCollection();

       for($i=0; $i<count($amount); $i++){

           $opt_val['value'] = $amount[$i]["city_name"];
           $opt_val['label'] = $amount[$i]["city_name"];

           $allOptions[] = $opt_val;
       }

       return $allOptions;

   }


    /** At the moment getting all warehouses. TODO Make dependency on the selected city
     * @return array
     */
    protected function getWarehouses()
    {
        $amount = $this->warehouseRepository->getCollection();

        for($i=0; $i<count($amount); $i++){

            $opt_val['value'] = $amount[$i]["warehouse_name"];
            $opt_val['label'] = $amount[$i]["warehouse_name"];

            $allOptions[] = $opt_val;
        }

        return $allOptions;

    }
}
