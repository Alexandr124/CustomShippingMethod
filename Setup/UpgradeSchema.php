<?php

namespace Vaimo\NovaPoshta\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();


        if (version_compare($context->getVersion(), '1.0.7', '<')) {
            $setup->getConnection()->addColumn(
                $setup->getTable('vaimo_novaposhta_cities'),
                'original_id',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => false,
                    'default' => '',
                    'comment' => 'Original city id'
                ]
            );

        }

        if (version_compare($context->getVersion(), '1.0.10', '<')) {
            $setup->getConnection()->addColumn(
                $setup->getTable('quote_address'),
                'novaposhta_warehouse',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table ::TYPE_TEXT,
                    'nullable' => true,
                    'default' => NULL,
                    'length' => 255,
                    'comment' => 'Mob Type'
                ]
            );
            $setup->getConnection()->addColumn(
                $setup->getTable('sales_order_address'),
                'novaposhta_warehouse',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table ::TYPE_TEXT,
                    'nullable' => true,
                    'default' => NULL,
                    'length' => 255,
                    'comment' => 'Mob Type'
                ]
            );
            $setup->endSetup();
        }

        $setup->endSetup();
    }
}