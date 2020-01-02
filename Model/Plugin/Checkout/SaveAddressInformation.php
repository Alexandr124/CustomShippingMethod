<?php

namespace Vaimo\NovaPoshta\Model\Plugin\Checkout;

/** AN OLD PLUGIN. NOT USED FOR THIS MOMENT
 *
 * Class SaveAddressInformation
 * @package Vaimo\NovaPoshta\Model\Plugin\Checkout
 */
class SaveAddressInformation
{
    /**
     * @var \Magento\Quote\Model\QuoteRepository
     */
    protected $quoteRepository;

    /**
     * SaveAddressInformation constructor.
     * @param \Magento\Quote\Model\QuoteRepository $quoteRepository
     */
    public function __construct(
        \Magento\Quote\Model\QuoteRepository $quoteRepository
    )
    {
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param $cartId
     * @param \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    )
    {
        $shippingAddress = $addressInformation->getShippingAddress();
        $shippingAddressExtensionAttributes = $shippingAddress->getExtensionAttributes();
        if ($shippingAddressExtensionAttributes) {
            $customField = $shippingAddressExtensionAttributes->getNovaposhtaWarehouse();
            $shippingAddress->setNovaposhtaWarehouse($customField);
        }

    }
}


