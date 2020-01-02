<?php
/**
* *
*  @author DCKAP Team
*  @copyright Copyright (c) 2018 DCKAP (https://www.dckap.com)
*  @package Dckap_CustomFields
*/

namespace Vaimo\NovaPoshta\Observer;

/**
* Class SaveCustomFieldsInOrder
* @package Vaimo\NovaPoshta\Observer
*/
class SaveCustomFieldsInOrder implements \Magento\Framework\Event\ObserverInterface
{
   /**
    * @param \Magento\Framework\Event\Observer $observer
    * @return $this
    */
   public function execute(\Magento\Framework\Event\Observer $observer)
  {

     $order = $observer->getEvent()->getOrder();
     $quote = $observer->getEvent()->getQuote();

       $order->setData("novaposhta_city_custom_field",$quote->getNovaposhtaCityCustomField());
       $order->setData("novaposhta_warehouse_custom_field",$quote->getNovaposhtaWarehouseCustomField());

     return $this;
  }
}
