<?php

namespace Learn\NovaPoshta\Model\Provider;

use Learn\NovaPoshta\Model\CityRepository;
use Learn\NovaPoshta\Model\ResourceModel\City\Collection as CityCollection;
use Learn\NovaPoshta\Model\WarehouseRepository;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class CheckoutConfigProvider
 * @package Learn\NovaPoshta\Model\Provider
 */
class CheckoutConfigProvider implements ConfigProviderInterface
{

    const FIELDS_ACTIVE = 'carriers/nova_poshta_shipping/active';
    /**
     * @var CityCollection
     */
    private $cityCollection;

    /**
     * @var CityRepository
     */
    private $cityRepository;

    /**
     * @var WarehouseRepository
     */
    private $warehouseRepository;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * CheckoutConfigProvider constructor.
     * @param CityCollection $cityCollection
     * @param CityRepository $cityRepository
     * @param ScopeConfigInterface $scopeConfig
     * @param WarehouseRepository $warehouseRepository
     */
    public function __construct(
        CityCollection $cityCollection,
        CityRepository $cityRepository,
        ScopeConfigInterface $scopeConfig,
        WarehouseRepository $warehouseRepository
    ) {
        $this->cityCollection = $cityCollection;
        $this->cityRepository = $cityRepository;
        $this->scopeConfig = $scopeConfig;
        $this->warehouseRepository = $warehouseRepository;
    }

    /**
     * @return array|mixed
     */
    public function getConfig()
    {
        $warehouseData = [];
        $warehouses = $this->warehouseRepository->getAllWarehouses();

        foreach ($warehouses as $warehouse) {
            $warehouseData[$warehouse['city_id']][] = $warehouse;
        }

        $donationConfig['novaPoshtaConfig'] = [
            'module_enabled' => $this->scopeConfig->getValue(self::FIELDS_ACTIVE),
            'cities' => $this->cityRepository->getAllCities() ?: false,
            'warehouses' => $warehouseData ?: false,
        ];
        return $donationConfig;
    }
}
