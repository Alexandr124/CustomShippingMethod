<?php

namespace Vaimo\NovaPoshta\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

/**
 * Class UpgradeSchema
 * @package Vaimo\NovaPoshta\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
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

        if (version_compare($context->getVersion(), '2.0.0', '<')) {
            $eavTable1 = $setup->getTable('quote');
            $eavTable2 = $setup->getTable('sales_order');

            $columns = [
                'novaposhta_city_custom_field' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => true,
                    'comment' => 'City Select Field',
                ],

                'novaposhta_warehouse_custom_field' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => true,
                    'comment' => 'Warehouse & Street Select Field',
                ],
            ];

            $connection = $setup->getConnection();
            foreach ($columns as $name => $definition) {
                $connection->addColumn($eavTable1, $name, $definition);
                $connection->addColumn($eavTable2, $name, $definition);
            }

        }

        if (version_compare($context->getVersion(), '2.0.1', '<')) {
            $table = $setup->getConnection()->newTable(
                $setup->getTable('vaimo_novaposhta_warehouses')
            )->addColumn(
                'warehouse_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Id'
            )->addColumn(
                'warehouse_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Name'
            )->addColumn(
                'cityRef',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Reference'
            )->setComment(
                'Table with the Departments list'
            );
            $setup->getConnection()->createTable($table);
        }
        $setup->endSetup();
    }
}



