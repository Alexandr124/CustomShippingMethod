<?php

namespace Vaimo\NovaPoshta\Cron;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

use Vaimo\NovaPoshta\Api\CityRepositoryInterface;
use Vaimo\NovaPoshta\Api\WarehouseRepositoryInterface;
use Vaimo\NovaPoshta\Controller\Adminhtml\Index\Delete;

/**
 * Class RefreshData
 * @package Vaimo\NovaPoshta\Cron
 */
class RefreshData extends \Magento\Backend\App\Action
{
    /**The one must be setted from the admin panel. TODO list
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
     * @var \Vaimo\NovaPoshta\Model\CityFactory
     */
    private $cityFactory;
    /**
     * @var \Vaimo\NovaPoshta\Model\WarehouseFactory
     */
    private $warehouseFactory;
    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var \Magento\Framework\HTTP\ZendClientFactory
     */
    private $_httpClientFactory;

    /**
     * RefreshData constructor.
     * @param Context $context
     * @param CityRepositoryInterface $cityRepository
     * @param WarehouseRepositoryInterface $warehouseRepository
     * @param \Vaimo\NovaPoshta\Model\CityFactory $cityFactory
     * @param \Vaimo\NovaPoshta\Model\WarehouseFactory $warehouseFactory
     * @param \Magento\Framework\HTTP\ZendClientFactory $httpClientFactory
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Context $context,
        CityRepositoryInterface $cityRepository,
        WarehouseRepositoryInterface $warehouseRepository,
        \Vaimo\NovaPoshta\Model\CityFactory $cityFactory,
        \Vaimo\NovaPoshta\Model\WarehouseFactory $warehouseFactory,
        \Magento\Framework\HTTP\ZendClientFactory $httpClientFactory,
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
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        Delete::clearDB();

        $citiesApiJson = $this->_getCitiesFromServer();
        $citiesApi = json_decode($citiesApiJson);
        if (property_exists($citiesApi, 'success') && $citiesApi->success === true) {
            $this->_syncWithDb($citiesApi->data);

        } else {
            $this->messageManager->addError($citiesApi->message);
        }

        Delete::clearWarehouseDB();

        $warehousesApiJson = $this->_getWarehousesFromServer();
        $warehousesApi = json_decode($warehousesApiJson);
        if (property_exists($warehousesApi, 'success') && $warehousesApi->success === true) {
            $this->_syncWarehousesWithDb($warehousesApi->data);

        } else {
            $this->messageManager->addError($warehousesApi->message);
        }
    }

    /**
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

    /**
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

    /**
     * @param $citiesApi
     */
    private function _syncWithDb($citiesApi)
    {
        for($i=0; $i<count($citiesApi); $i++) {
            $city = $this->cityFactory->create();
            $city->setData("city_name", $citiesApi[$i]->Description);
            $city->setData("ref", $citiesApi[$i]->Ref);
            $city->setData("original_id", $citiesApi[$i]->CityID);

            $this->cityRepository->save($city);

            $city->setData("city_id", null);
        }
    }

    /**
     * @param $warehousesApi
     */
    private function _syncWarehousesWithDb($warehousesApi)
    {
        for($i=0; $i<count($warehousesApi); $i++) {
            $warehouse = $this->warehouseFactory->create();
            $warehouse->setData("warehouse_name", $warehousesApi[$i]->Description);
            $warehouse->setData("city_ref", $warehousesApi[$i]->CityRef);

            $this->warehouseRepository->save($warehouse);

            $warehouse->setData("warehouse_id", null);
        }
    }
}

