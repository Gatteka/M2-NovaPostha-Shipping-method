<?php

namespace Learn\NovaPoshta\Controller\Adminhtml\Update;

use Exception;
use Learn\NovaPoshta\Model\CityFactory;
use Learn\NovaPoshta\Model\CityRepository;
use Learn\NovaPoshta\Model\ResourceModel\City as CityResource;
use Learn\NovaPoshta\Model\ResourceModel\City\Collection as CityCollection;
use Learn\NovaPoshta\Model\ResourceModel\Warehouse as WarehouseResource;
use Learn\NovaPoshta\Model\WarehouseRepository;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class UpdateWarehouses
 * @package Learn\NovaPoshta\Controller\Adminhtml\Update
 */
class UpdateWarehouses extends Action
{

    const PATH_TO_API_KEY = 'carriers/nova_poshta_shipping/shipping_nova_poshta_api_key';

    const PATH_TO_SENDER_CITY_NAME = 'carriers/nova_poshta_shipping/sender_city';

    /** @var PageFactory */
    public $resultPageFactory;

    /** @var Curl */
    public $curl;

    /** @var Json */
    public $serializer;

    /** @var ScopeConfigInterface */
    public $scopeConfig;

    /** @var CityFactory */
    public $factory;

    /** @var ManagerInterface */
    public $messageManager;

    /** @var CityCollection  */
    private $cityCollection;

    /** @var CityRepository */
    private $cityRepository;

    /** @var WarehouseRepository */
    private $warehouseRepository;

    /**
     * UpdateWarehouses constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param CityRepository $cityRepository
     * @param WarehouseRepository $warehouseRepository
     * @param CityFactory $factory
     * @param Json $serializer
     * @param Curl $curl
     * @param CityCollection $cityCollection
     * @param ManagerInterface $messageManager
     * @param Context $context
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        CityRepository $cityRepository,
        WarehouseRepository $warehouseRepository,
        CityFactory $factory,
        Json $serializer,
        Curl $curl,
        CityCollection $cityCollection,
        ManagerInterface $messageManager,
        Context $context
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->cityRepository = $cityRepository;
        $this->warehouseRepository = $warehouseRepository;
        $this->factory = $factory;
        $this->serializer = $serializer;
        $this->curl = $curl;
        $this->cityCollection = $cityCollection;
        $this->messageManager = $messageManager;
        parent::__construct($context);
    }

    /**
     * Get all Cities and Warehouses data from API and save it to Database using repositories
     *
     * @return ResponseInterface|ResultInterface|void
     * @throws Exception
     */
    public function execute()
    {
        $cityData = [];
        $warehousesData = [];

        $cities = $this->getAllCities()['data'];
        $warehouses = $this->getAllWarehouses()['data'];
        if (!$cities) {
            return;
        }
        $this->cityCollection->getConnection()->truncateTable(CityResource::TABLE_NAME);
        $this->cityCollection->getConnection()->truncateTable(WarehouseResource::TABLE_NAME);

        foreach ($cities as $city) {
            $cityData [] = [
                'city_id' => $city['CityID'],
                'city_name' => $city['Description'],
                'city_type' => $city['SettlementTypeDescription'] ?? null,
                'ref' => $city['Ref']
            ];
        }

        foreach ($warehouses as $warehouse) {
            $warehousesData [] = [
                'warehouse_desc' => $warehouse['ShortAddress'],
                'city_id' => $this->getCityIdByName($cityData, $warehouse['CityDescription'])
            ];
        }

        $this->warehouseRepository->saveWarehousesByCity($warehousesData);
        $this->cityRepository->createAndSaveCity($cityData);

        $this->messageManager->addSuccessMessage(__('Warehouses and cities data updated'));
    }

    /**
     *  Get city id by city name.
     *  For relation with warehouses.
     *
     * @param $cityData
     * @param $name
     * @return string
     */
    public function getCityIdByName($cityData, $name)
    {
        foreach ($cityData as $city) {
            if ($city['city_name'] === $name) {
                return $city['city_id'];
            }
        }
        return null;
    }

    /**
     * API call to get all cities.
     *
     * @return array
     */
    public function getAllCities()
    {
        $client = $this->curl;
        $client->addHeader('content-type', 'application/json');
        $client->post(
            'https://api.novaposhta.ua/v2.0/json/',
            '{
             "modelName": "AddressGeneral",
             "calledMethod": "getCities",
             "methodProperties": {},
              "apiKey": "' . $this->scopeConfig->getValue(self::PATH_TO_API_KEY) . '"
             }'
        );
        return $this->serializer->unserialize($client->getBody());
    }

    /**
     * API call to get all warehouses.
     *
     * @return array
     */
    public function getAllWarehouses()
    {
        $client = $this->curl;
        $client->addHeader('content-type', 'application/json');
        $client->post(
            'https://api.novaposhta.ua/v2.0/json/',
            '{
             "modelName": "AddressGeneral",
             "calledMethod": "getWarehouses",
             "methodProperties": {
              "Language": "ru"
             },
             "apiKey": "' . $this->scopeConfig->getValue(self::PATH_TO_API_KEY) . '"
             }'
        );
        return $this->serializer->unserialize($client->getBody());
    }
}
