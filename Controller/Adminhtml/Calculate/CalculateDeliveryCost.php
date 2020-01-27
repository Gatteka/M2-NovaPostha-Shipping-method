<?php

namespace Learn\NovaPoshta\Controller\Adminhtml\Calculate;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class CalculateDeliveryCost
 * @package Learn\NovaPoshta\Controller\Adminhtml\Calculate
 */
class CalculateDeliveryCost extends Action
{
    const PATH_TO_API_KEY = 'carriers/nova_poshta_shipping/shipping_nova_poshta_api_key';

    const PATH_TO_SENDER_CITY_NAME = 'carriers/nova_poshta_shipping/sender_city';

    private $curl;

    private $scopeConfig;

    private $serializer;

    private $context;

    /**
     * CalculateDeliveryCost constructor.
     * @param Curl $curl
     * @param ScopeConfigInterface $scopeConfig
     * @param Json $serializer
     * @param Context $context
     */
    public function __construct(
        Curl $curl,
        ScopeConfigInterface $scopeConfig,
        Json $serializer,
        Context $context
    ) {
        $this->curl = $curl;
        $this->serializer = $serializer;
        $this->context = $context;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return false
     */
    public function execute()
    {
        if ($this->scopeConfig->getValue(self::PATH_TO_SENDER_CITY_NAME) == null) {
            $this->messageManager->addErrorMessage(__('Sender city is not specified.'));
            return false;
        }
        $this->getDeliveryCostBySenderCity();
    }

    /**
     * Get Ref (unique id for city in NP API) by City name(string) in admin
     *
     * @return array
     */
    public function getRefBySenderCityName()
    {
        $client = $this->curl;
        $client->addHeader('content-type', 'application/json');
        $client->post(
            'https://api.novaposhta.ua/v2.0/json/',
            '{
             "modelName": "AddressGeneral",
             "calledMethod": "getCities",
             "methodProperties": {
             "FindByString": "' . $this->scopeConfig->getValue(self::PATH_TO_SENDER_CITY_NAME) . '"
             },
              "apiKey": "' . $this->scopeConfig->getValue(self::PATH_TO_API_KEY) . '"
             }'
        );
        return $this->serializer->unserialize($client->getBody());
    }
    /**
     * Get delivery cost by sender City for all cities.
     *
     * @return array
     */
    public function getDeliveryCostBySenderCity()
    {
        $this->getRefBySenderCityName();
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
}
