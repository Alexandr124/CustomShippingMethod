<?php
namespace Vaimo\NovaPoshta\Block;

use Magento\Framework\View\Element\Template;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magegain\Novaposhta\Api\CityRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

class City extends \Magento\Framework\View\Element\Template
{
    protected $temp;
//
//    public function __construct(Template\Context $context, array $data = [])
//    {
//        parent::__construct($context, $data);

//    }
//
   public function showCity(){
        echo $this->temp;

   }

    const POST_API_KEY = "24b06ad5b19dbf9166aa18605552f593";
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    private $cityRepository;
    private $cityFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var \Magento\Framework\HTTP\ZendClientFactory
     */
    private $_httpClientFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param CityRepositoryInterface $cityRepository
     * @param \Magegain\Novaposhta\Model\CityFactory $cityFactory
     * @param \Magento\Framework\HTTP\ZendClientFactory $httpClientFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Template\Context $context,
        array $data = [],
//        Context $context,
        PageFactory $resultPageFactory,
        CityRepositoryInterface $cityRepository,
        \Magegain\Novaposhta\Model\CityFactory $cityFactory,
        \Magento\Framework\HTTP\ZendClientFactory $httpClientFactory,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context, $data);
        $this->resultPageFactory = $resultPageFactory;
        $this->cityRepository = $cityRepository;
        $this->cityFactory = $cityFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->_httpClientFactory = $httpClientFactory;
        $this->scopeConfig = $scopeConfig;

        $this->temp="Test";
    }

    /**
     * Check the permission to run it
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magento_Cms::page');
    }


    public function execute()
    {

        try {
            $citiesApiJson = $this->_getCitiesFromServer();
        } catch (\Zend_Http_Client_Exception $e) {
        }
        $citiesApi = json_decode($citiesApiJson);
        $cityArray = $citiesApi->data;
        $t2 = $cityArray[77]->Description;
//        $t3=3;
        $i = 0;
        for ($i = 0; $i<count($cityArray); $i++){
            $temp = $cityArray[$i]->Description;
            echo $temp; ?> <br> <?php
        }


        return $t2;

    }

    /**
     * Get cities from api
     *
     *
     * @return string|json
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


}


