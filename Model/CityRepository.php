<?php

namespace Vaimo\NovaPoshta\Model;

use Magento\Framework\Api\SearchCriteriaInterface;

use Magento\Framework\Exception\AlreadyExistsException;
use Vaimo\NovaPoshta\Api\CityRepositoryInterface;
use Vaimo\NovaPoshta\Model\ResourceModel\City as CityResource;
use Vaimo\NovaPoshta\Model\ResourceModel\City\Collection;
use Vaimo\NovaPoshta\Model\ResourceModel\City\CollectionFactory;

class CityRepository implements CityRepositoryInterface
{

    private $cityResource;
    protected $resourceModel;
    private $cityFactory;
    private $citycollection;

    private $collectionFactory;

    public function __construct(
        CityResource $cityResource,
        CityFactory $cityFactory,
        CollectionFactory $collectionFactory,
        Collection $citycollection,
        \Vaimo\QuoteModule\Model\ResourceModel\Quote $resourceModel
    ) {

        $this->cityResource = $cityResource;
        $this->cityFactory = $cityFactory;
        $this->collectionFactory = $collectionFactory;
        $this->citycollection = $citycollection;
        $this->resourceModel  = $resourceModel;
    }


    public function save(\Vaimo\NovaPoshta\Model\CityInterface $city)
    {
        try {
            $this->cityResource->save($city);
        } catch (\Exception $exception) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(__($exception->getMessage()));
        }
        return $city;
    }


    public function getById($id)
    {
        $city = $this->cityFactory->create();
        $this->cityResource->load($city, $id);
        if (!$city->getId()) {
            throw new NoSuchEntityException(__('City with id "%1" does not exist.', $id));
        }
        return $city;
    }

    public function getCollection(){

        $collection =  $this->citycollection->getData();
        return $collection;
    }
}
