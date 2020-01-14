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
    protected $allOptions=[];

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

       $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
       ['shippingAddress']['component']='Vaimo_NovaPoshta/js/shipping/main';

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
           'filterOptions' => true,
           'showCheckbox' => true,
           'chipsEnabled' => true,
           'disableLabel' => true,
           'multiple' => false,
           'validation' => $validation,
           'sortOrder' => 2,
           'id' => 'custom_shipping_field[novaposhta_city_custom_field]'
       ];


       $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
       ['shippingAddress']['children']['custom-shipping-method-fields']['children']['novaposhta_warehouse_custom_field'] = [
           'component' => 'Vaimo_NovaPoshta/js/shipping/warehouse',
           'config' => [
               'customScope' => 'customShippingMethodFields',
               'template' => 'ui/form/field',
               'elementTmpl' => "ui/form/element/select",
               'id' => "novaposhta_warehouse_custom_field"
           ],
           'dataScope' => 'customShippingMethodFields.custom_shipping_field[novaposhta_warehouse_custom_field]',
           'label' => "NovaPoshta Warehouse",
       //    'options' => $this->getWarehouses(),
           'provider' => 'checkoutProvider',
           'visible' => true,
           'validation' => [],
           'sortOrder' => 4,
           'id' => 'custom_shipping_field[novaposhta_warehouse_custom_field]'
       ];




       return $jsLayout;
   }


    /**
     * @return array
     */
    protected function getCities()
    {
       $amount = $this->repository->getCollection();

       $allOptions = [];

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


        if (isset($_GET["w1"]) && isset($_GET["w2"])) {
      // Put the two words together with a space in the middle to form "hello world"
      $hello = $_GET["w1"] . " " . $_GET["w2"];


      // Print out some JavaScript with $hello stuck in there which will put "hello world" into the javascript.

      echo "<script language='text/javascript'>function sayHiFromPHP() { alert('Just wanted to say $hello!'); }</script>";

   }


        $allOptions = [];

        for($i=0; $i<count($amount); $i++){

            $opt_val['value'] = $amount[$i]["warehouse_name"];
            $opt_val['label'] = $amount[$i]["warehouse_name"];

            $allOptions[] = $opt_val;
        }

        return $allOptions;

    }
}
