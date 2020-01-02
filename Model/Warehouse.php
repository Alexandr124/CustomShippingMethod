<?php

namespace Vaimo\NovaPoshta\Model;


use Vaimo\NovaPoshta\Model\ResourceModel\Warehouse as ResourceModel;

/**
 * Class Warehouse
 * @package Vaimo\NovaPoshta\Model
 */
class Warehouse extends \Magento\Framework\Model\AbstractExtensibleModel implements WarehouseInterface
{

    /**
     *
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @param $warehouse_id
     * @return Warehouse
     */
    public function setWarehouseId($warehouse_id)
    {
        return $this->setData('warehouse_id', $warehouse_id);
    }

    /**
     * @param $warehouse_name
     * @return Warehouse
     */
    public function setWarehouseName($warehouse_name)
    {
        return $this->setData('warehouse_name', $warehouse_name);
    }


    /**
     * @param $ref
     * @return Warehouse
     */
    public function setWarehouseRef($ref)
    {
        return $this->setData('city_ref', $ref);
    }
}
