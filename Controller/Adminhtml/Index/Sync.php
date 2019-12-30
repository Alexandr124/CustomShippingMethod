<?php

namespace Vaimo\NovaPoshta\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

use Vaimo\NovaPoshta\Api\CityRepositoryInterface;

class Sync extends \Magento\Backend\App\Action
{
    const POST_API_KEY = "24b06ad5b19dbf9166aa18605552f593";

    protected $resultPageFactory;
    private $cityRepository;
    private $cityFactory;
    protected $model;
    private $searchCriteriaBuilder;

    private $_httpClientFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        CityRepositoryInterface $cityRepository,
        \Vaimo\NovaPoshta\Model\CityFactory $cityFactory,
        \Magento\Framework\HTTP\ZendClientFactory $httpClientFactory,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->cityRepository = $cityRepository;
        $this->cityFactory = $cityFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->_httpClientFactory = $httpClientFactory;
        $this->scopeConfig = $scopeConfig;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magento_Cms::page');
    }

    public function execute()
    {
        Delete::clearDB();

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

    protected function getModel()
    {
        if (null === $this->model) {
            $this->model = $this->cityFactory->create();
        }
        return $this->model;
    }

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

    private function _syncWithDb($citiesApi)
    {
//        $currentCitiesIds = $this->_getCitiesIdArray();
//        foreach ($citiesApi as $key => $cityApi) {
//            $cityApiId = $cityApi->CityID;
//            if (isset($currentCitiesIds[$cityApiId])) {
//                continue;
//            } else {
//                $this->_addNewCity($cityApi);
//            }
//        }

//        $city->setData($citiesApi);
        for($i=0; $i<count($citiesApi); $i++) {
            $city = $this->getModel();
            $city->setData("city_name", $citiesApi[$i]->Description);
            $city->setData("ref", $citiesApi[$i]->Ref);
            $city->setData("original_id", $citiesApi[$i]->CityID);

            $this->cityRepository->save($city);

            $city->setData("city_id", null);
        }
        $t1=1;
    }

    /**
     * @return array
     */
    private function _getCitiesIdArray()
    {
        $citiesCollection = $this->_getCitiesCollection();
        $idsArray = [];
        foreach ($citiesCollection as $key => $city_model) {
            $idsArray[$city_model->getCityId()] = '';
        }
        return $idsArray;
    }

    protected function _getCitiesCollection()
    {
        return $this->cityRepository->getList(
            $this->searchCriteriaBuilder->create()
        )->getItems();
    }

    private function _addNewCity($cityApi)
    {
        $modelCity = $this->cityFactory->create();
        $modelCity->setCityId($cityApi->CityID);
        $modelCity->setCityName($cityApi->Description);
        $modelCity->setCityNameRu($cityApi->DescriptionRu);
        $modelCity->setRef($cityApi->Ref);
        $this->cityRepository->save($modelCity);
    }
}