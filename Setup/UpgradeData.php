<?php

namespace Vaimo\NovaPoshta\Setup;

use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class UpgradeData implements UpgradeDataInterface
{
    protected $customerSetupFactory;

    private $attributeSetFactory;

    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
    }


    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();

        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);
        if (version_compare($context->getVersion(), '1.0.12') < 0) {
            $customerSetup->addAttribute(
                \Magento\Customer\Model\Customer::ENTITY,
                'novaposhta_warehouse',
                [
                    'type' => 'text',
                    'label' => 'NovaPoshtaWarehouse',
                    'input' => 'text',
                    'required' => false,
                    'visible' => true,
                    'user_defined' => true,
                    'sort_order' => 100,
                    'position' => 100,
                    'system' => 0,
                ]
            );
            $Attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'novaposhta_warehouse')
                ->addData([
                    'attribute_set_id' => 1,
                    'attribute_group_id' => 1,
                    'used_in_forms' => ['adminhtml_customer','checkout_register', 'customer_account_create', 'customer_account_edit','adminhtml_checkout','adminhtml_customer_address','customer_register_address', 'customer_address_edit'],
                ]);

            $Attribute->save();


        }

        $setup->endSetup();
    }
}