<?php
/**
 * Created by PhpStorm.
 * User: alexander
 * Date: 2019-12-04
 * Time: 12:11
 */

namespace Vaimo\NovaPoshta\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Warehouse
 * @package Vaimo\NovaPoshta\Model\ResourceModel
 */
class Warehouse extends AbstractDb
{
    /**
     *
     */
    protected function _construct()
    {
        $this->_init('vaimo_novaposhta_warehouses', 'warehouse_id');
    }
}