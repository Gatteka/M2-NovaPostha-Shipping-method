<?php

namespace Learn\NovaPoshta\Model;

use Exception;
use Learn\NovaPoshta\Model\ResourceModel\City as CityResource;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;

/**
 * Class WarehouseRepository
 * @package Learn\NovaPoshta\Model
 */
class CityRepository
{
    /**
     * @var Warehouse
     */
    private $resource;

    /**
     * @var CityFactory
     */
    private $factory;

    /**
     * @var AdapterInterface
     */
    private $connection;

    /**
     * CityRepository constructor.
     * @param CityResource $resource
     * @param CityFactory $factory
     * @param ResourceConnection $connection
     */
    public function __construct(
        CityResource $resource,
        CityFactory $factory,
        ResourceConnection $connection
    ) {
        $this->resource = $resource;
        $this->connection = $connection->getConnection();
        $this->factory = $factory;
    }

    public function save(City $model)
    {
        $this->resource->save($model);

        return $model;
    }

    /**
     * Create and save Cities models
     *
     * @param array $cityData
     * @return bool
     * @throws Exception
     */
    public function createAndSaveCity(array $cityData)
    {
        try {
            $data = $this->setBulkData([], $cityData);

            foreach ($data as $bulkData) {
                $this->connection->insertMultiple(CityResource::TABLE_NAME, $bulkData);
            }
        } catch (Exception $e) {
            throw $e;
        }

        return true;
    }

    /**
     * @return array
     */
    public function getAllCities(): array
    {
        $select = $this->connection->select()->from(CityResource::TABLE_NAME);
        if (!$select) {
            return [];
        }

        return $this->connection->fetchAssoc($select);
    }

    /**
     * @param $bulkData
     * @param $remainData
     * @return array
     */
    public function setBulkData($bulkData, $remainData)
    {
        if (!$remainData) {
            return $bulkData;
        }
        $bulkData[] = array_slice($remainData, 0, 200);
        $remainData = array_slice($remainData, 200);

        return $this->setBulkData($bulkData, $remainData);
    }

    /**
     * @param $model
     * @throws Exception
     */
    public function delete(City $model)
    {
        $this->resource->delete($model);
    }

    public function getById(int $modelId): City
    {
        $model = $this->factory->create();
        $this->resource->load($model, $modelId);

        return $model;
    }
}
