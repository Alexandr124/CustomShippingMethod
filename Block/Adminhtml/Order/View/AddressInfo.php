<?php
namespace Vaimo\NovaPoshta\Block\Adminhtml\Order\View;

use Magento\Backend\Block\Template\Context;
/**
 * Class AddressInfo
 * @package Vaimo\NovaPoshta\Block\Adminhtml\Order\View
 */
class AddressInfo extends \Magento\Backend\Block\Template
{
    protected $orderRepository;
//    public function __construct(
//        \Magento\Backend\Model\Session\Quote $sessionQuote,
//        Context $context) {
//
//        $this->_sessionQuote = $sessionQuote;
//        parent::__construct($context);
//    }

    public function __construct(
        Context $context,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
    ){
        $this->orderRepository = $orderRepository;
        parent::__construct($context);
    }

    /**
     *@param int $id The order ID.
     */
    public function getOrderData($id)
    {
        try {
            $order = $this->orderRepository->get($id);
        } catch (NoSuchEntityException $e) {
            throw new \Magento\Framework\Exception\LocalizedException(__('This order no longer exists.'));
        }
        return $order;
    }

    /**TestBlock. Implementing additional info to the admin panel.
     *
     */
    public function showCity(){

        return $this->getPostData()['novaposhta_city_custom_field'];

     }

     public function showWarehouse(){

         return $this->getPostData()['novaposhta_warehouse_custom_field'];

     }

     public function getPostData(){
         $order_id =  $this->_request->getParam('order_id');
         $order_params = $this->getOrderData($order_id);

         $order_data = $order_params->getData();

         return $order_data;
     }



}