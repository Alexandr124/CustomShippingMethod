<?php

namespace Vaimo\NovaPoshta\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Framework\HTTP\ZendClientFactory;
use Magento\Framework\View\Result\PageFactory;

use Vaimo\NovaPoshta\Api\CityRepositoryInterface;
use Vaimo\NovaPoshta\Api\WarehouseRepositoryInterface;
use Vaimo\NovaPoshta\Model\CityFactory;
use Vaimo\NovaPoshta\Model\WarehouseFactory;

/**
 * Class Sync for updating data in DB via API
 * @package Vaimo\NovaPoshta\Controller\Adminhtml\Index
 */
class Sync extends \Magento\Backend\App\Action
{
    /** Personal API key. TODO:make a field to fill in from admin panel
     *
     */
    const POST_API_KEY = "24b06ad5b19dbf9166aa18605552f593";

    /**
     * @var CityRepositoryInterface
     */
    private $cityRepository;
    /**
     * @var WarehouseRepositoryInterface
     */
    private $warehouseRepository;
    /**
     * @var CityFactory
     */
    private $cityFactory;
    /**
     * @var WarehouseFactory
     */
    private $warehouseFactory;
    /**
     * @var
     */
    private $model;
    /**
     * @var
     */
    private $warehouseModel;
    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var ZendClientFactory
     */
    private $_httpClientFactory;

    /**
     * Sync constructor.
     * @param Context $context
     * @param CityRepositoryInterface $cityRepository
     * @param WarehouseRepositoryInterface $warehouseRepository
     * @param CityFactory $cityFactory
     * @param WarehouseFactory $warehouseFactory
     * @param ZendClientFactory $httpClientFactory
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Context $context,
        CityRepositoryInterface $cityRepository,
        WarehouseRepositoryInterface $warehouseRepository,
        CityFactory $cityFactory,
        WarehouseFactory $warehouseFactory,
        ZendClientFactory $httpClientFactory,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);
        $this->warehouseRepository = $warehouseRepository;
        $this->cityRepository = $cityRepository;
        $this->cityFactory = $cityFactory;
        $this->warehouseFactory = $warehouseFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->_httpClientFactory = $httpClientFactory;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magento_Cms::page');
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        Delete::clearDB();
        $this->syncCities();

        Delete::clearWarehouseDB();
        $this->syncWarehouses();
    }

    /**Updating Cities. Calling funcs for making an API call to server and then save data to DB.
     *
     */
    protected function syncCities(){
        $citiesApiJson = $this->_getCitiesFromServer();
        $citiesApi = json_decode($citiesApiJson);
        if (property_exists($citiesApi, 'success') && $citiesApi->success === true) {
            $this->_syncWithDb($citiesApi->data);
            $this->messageManager->addSuccess(
                __('Synchronized successfully')
            );
            $this->_redirect('novaposhta/city/index');
        } else {
            $this->messageManager->addError(
                __('Newpost is not responding or responding incorrectly')
            );
            $this->messageManager->addError($citiesApi->message);
            $this->_redirect('novaposhta/city/index');
        }
    }

    /**The same as for Cities
     *
     */
    protected function syncWarehouses(){
        $warehousesApiJson = $this->_getWarehousesFromServer();
        $warehousesApi = json_decode($warehousesApiJson);
        if (property_exists($warehousesApi, 'success') && $warehousesApi->success === true) {
            $this->_syncWarehousesWithDb($warehousesApi->data);
            $this->messageManager->addSuccess(
                __('Synchronized successfully')
            );
            $this->_redirect('novaposhta/city/index');
        } else {
            $this->messageManager->addError(
                __('Newpost is not responding or responding incorrectly')
            );
            $this->messageManager->addError($warehousesApi->message);
            $this->_redirect('novaposhta/city/index');
        }

    }

    /** Getting City model
     * @return \Vaimo\NovaPoshta\Model\City
     */
    protected function getModel()
    {
        if (null === $this->model) {
            $this->model = $this->cityFactory->create();
        }
        return $this->model;
    }

    /** Getting Warehouse model
     * @return \Vaimo\NovaPoshta\Model\Warehouse
     */
    protected function getWarehouseModel()
    {
        if (null === $this->warehouseModel) {
            $this->warehouseModel = $this->warehouseFactory->create();
        }
        return $this->warehouseModel;
    }

    /** API request for Cities with such parameters as Request type, Called method and api key. Getting data in JSON format
     * @return string
     * @throws \Zend_Http_Client_Exception
     */
    private function _getCitiesFromServer()
    {
        $apiKey = self::POST_API_KEY;
        $client = $this->_httpClientFactory->create();
        $client->setUri('http://testapi.novaposhta.ua/v2.0/json/Address/getCities');
        $request = ['modelName' => 'Address', 'calledMethod' => 'getCities', 'apiKey' => $apiKey];
        $client->setConfig(['maxredirects' => 0, 'timeout' => 30]);
        $client->setRawData(utf8_encode(json_encode($request)));
        return $client->request(\Zend_Http_Client::POST)->getBody();
    }

    /** API request for Warehouses with such parameters as Request type, Called method and api key. Getting data in JSON format
     * @return string
     * @throws \Zend_Http_Client_Exception
     */
    private function _getWarehousesFromServer()
    {
        $apiKey = self::POST_API_KEY;
        $client = $this->_httpClientFactory->create();
        $client->setUri('http://testapi.novaposhta.ua/v2.0/json/AddressGeneral/getWarehouses');
        $request = ['modelName' => 'Address', 'calledMethod' => 'getWarehouses', 'apiKey' => $apiKey];
        $client->setConfig(['maxredirects' => 0, 'timeout' => 30]);
        $client->setRawData(utf8_encode(json_encode($request)));
        return $client->request(\Zend_Http_Client::POST)->getBody();
    }

    /** Saving decoded Cities info to "vaimo_novaposhta_cities" table
     * @param $citiesApi
     */
    private function _syncWithDb($citiesApi)
    {
        for($i=0; $i<count($citiesApi); $i++) {
            $city = $this->getModel();
            $city->setData("city_name", $citiesApi[$i]->Description);
            $city->setData("ref", $citiesApi[$i]->Ref);
            $city->setData("original_id", $citiesApi[$i]->CityID);

            $this->cityRepository->save($city);

            $city->setData("city_id", null);
        }
    }

    /**Saving decoded Cities info to "vaimo_novaposhta_warehouses" table
     * @param $warehousesApi
     */
    private function _syncWarehousesWithDb($warehousesApi)
    {
        for($i=0; $i<count($warehousesApi); $i++) {
            $warehouse = $this->getWarehouseModel();
            $warehouse->setData("warehouse_name", $warehousesApi[$i]->Description);
            $warehouse->setData("city_ref", $warehousesApi[$i]->CityRef);

            $this->warehouseRepository->save($warehouse);

            $warehouse->setData("warehouse_id", null);
        }
    }


    /**
     * @return mixed
     */
    protected function _getCitiesCollection()
    {
        return $this->cityRepository->getList(
            $this->searchCriteriaBuilder->create()
        )->getItems();
    }

    /**
     * @return mixed
     */
    protected function _getWarehousesCollection()
    {
        return $this->warehouseRepository->getList(
            $this->searchCriteriaBuilder->create()
        )->getItems();
    }

}

