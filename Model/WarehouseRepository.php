<?php

namespace Vaimo\NovaPoshta\Model;

use Magento\Framework\Api\SearchCriteriaInterface;

use Magento\Framework\Exception\AlreadyExistsException;
use Vaimo\NovaPoshta\Api\WarehouseRepositoryInterface;
use Vaimo\NovaPoshta\Model\ResourceModel\Warehouse as WarehouseResource;
use Vaimo\NovaPoshta\Model\ResourceModel\Warehouse\Collection;
use Vaimo\NovaPoshta\Model\ResourceModel\Warehouse\CollectionFactory;

/**
 * Class WarehouseRepository
 * @package Vaimo\NovaPoshta\Model
 */
class WarehouseRepository implements WarehouseRepositoryInterface
{

    /**
     * @var WarehouseResource
     */
    private $warehouseResource;
    /**
     * @var
     */
    protected $resourceModel;
    /**
     * @var WarehouseFactory
     */
    private $warehouseFactory;
    /**
     * @var Collection
     */
    private $warehousecollection;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * WarehouseRepository constructor.
     * @param WarehouseResource $warehouseResource
     * @param WarehouseFactory $warehouseFactory
     * @param CollectionFactory $collectionFactory
     * @param Collection $warehousecollection
     */
    public function __construct(
        WarehouseResource $warehouseResource,
        WarehouseFactory $warehouseFactory,
        CollectionFactory $collectionFactory,
        Collection $warehousecollection
    ) {

        $this->warehouseResource = $warehouseResource;
        $this->warehouseFactory = $warehouseFactory;
        $this->collectionFactory = $collectionFactory;
        $this->warehousecollection = $warehousecollection;
    }


    /**
     * @param WarehouseInterface $warehouse
     * @return WarehouseInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Vaimo\NovaPoshta\Model\WarehouseInterface $warehouse)
    {
        try {
            $this->warehouseResource->save($warehouse);
        } catch (\Exception $exception) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(__($exception->getMessage()));
        }
        return $warehouse;
    }


    /**
     * @param $id
     * @return Warehouse
     */
    public function getById($id)
    {
        $warehouse = $this->warehouseFactory->create();
        $this->warehouseResource->load($warehouse, $id);
        if (!$warehouse->getId()) {
            throw new NoSuchEntityException(__('Warehouse with id "%1" does not exist', $id));
        }
        return $warehouse;
    }

    /**
     * @return array
     */
    public function getCollection(){

        $collection =  $this->warehousecollection->getData();
        return $collection;
    }
}
