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

    public function getList(SearchCriteriaInterface $searchCriteria)
    {

    }

//    private function convertCollectionToDataItemsArray(
//        Collection $collection
//    ) {
//
//        $examples = array_map(function (Index $city) {
//            $dataObject = $this->cityDataFactory->create();
//            $dataObject->setId($city->getId());
//            $dataObject->setCityId($city->getCityId());
//            $dataObject->setCityName($city->getCityName());
//            $dataObject->setCityNameRu($city->getCityNameRu());
//            $dataObject->setRef($city->getRef());
//            return $dataObject;
//        }, $collection->getItems());
//        return $examples;
//    }


}
