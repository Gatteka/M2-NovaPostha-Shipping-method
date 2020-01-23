<?php

namespace Learn\NovaPoshta\Model;

use Learn\NovaPoshta\Model\ResourceModel\Warehouse;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Exception\AlreadyExistsException;

/**
 * Class WarehouseRepository
 * @package Learn\NovaPoshta\Model
 */
class WarehouseRepository
{
    /**
     * @var Warehouse
     */
    private $resource;

    /**
     * @var AdapterInterface
     */
    public $connection;

    /**
     * WarehouseRepository constructor.
     * @param Warehouse $resource
     * @param ResourceConnection $connection
     */
    public function __construct(
        Warehouse $resource,
        ResourceConnection $connection
    ) {
        $this->resource = $resource;
        $this->connection = $connection->getConnection();
    }

    /**
     * @param $model
     * @return mixed
     * @throws AlreadyExistsException
     */
    public function save($model)
    {
        $this->resource->save($model);
        return $model;
    }

    /**
     * @param $model
     * @throws \Exception
     */
    public function delete($model)
    {
        $this->resource->delete($model);
    }

    /**
     * @return array
     */
    public function getAllWarehouses(): array
    {
        $select = $this->connection->select()->from(Warehouse::TABLE_NAME);
        if (!$select) {
            return [];
        }
        return $this->connection->fetchAssoc($select);
    }

    /**
     * @param array $warehousesData
     * @return bool
     * @throws \Exception
     */
    public function saveWarehousesByCity(array $warehousesData) : bool
    {
        $data = $this->setBulkData([], $warehousesData);
        try {
            foreach ($data as $bulkData) {
                $this->connection->insertMultiple(Warehouse::TABLE_NAME, $bulkData);
            }
        } catch (\Exception $e) {
            throw $e;
        }
        return true;
    }

    /**
     * @param $bulkData
     * @param $remainData
     * @return array
     */
    public function setBulkData(array $bulkData, $remainData): array
    {
        if (!$remainData) {
            return $bulkData;
        }
        $bulkData[] = array_slice($remainData, 0, 200);
        $remainData = array_slice($remainData, 200);
        return $this->setBulkData($bulkData, $remainData);
    }
}
