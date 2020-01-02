<?php
/**
 * Created by PhpStorm.
 * User: alexander
 * Date: 2019-11-10
 * Time: 22:21
 */

namespace Vaimo\NovaPoshta\Model\ResourceModel\Warehouse;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

use Vaimo\NovaPoshta\Model\Warehouse;
use Vaimo\NovaPoshta\Model\ResourceModel\Warehouse as WarehouseResource;

/**
 * Class Collection
 * @package Vaimo\NovaPoshta\Model\ResourceModel\Warehouse
 */
class Collection extends AbstractCollection
{
    /**
     *
     */
    protected function _construct()
    {
        $this->_init(Warehouse::class, WarehouseResource::class);
    }
}