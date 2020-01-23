<?php

namespace Learn\NovaPoshta\Model;

use Learn\NovaPoshta\Model\ResourceModel\City;
use Learn\NovaPoshta\Model\ResourceModel\DestinationAddress as DestinationAddressResource;
use Learn\NovaPoshta\Model\ResourceModel\Warehouse;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Learn\NovaPoshta\Model\DestinationAddress;

/**
 * Class DestinationAddressRepository
 * @package Learn\NovaPoshta\Model
 */
class DestinationAddressRepository
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
     * @param DestinationAddress $resource
     * @param ResourceConnection $connection
     */
    public function __construct(
        DestinationAddress $resource,
        ResourceConnection $connection
    ) {
        $this->resource = $resource;
        $this->connection = $connection->getConnection();
    }

    public function save(DestinationAddress $model)
    {
        $this->resource->save($model);

        return $model;
    }

    public function delete(DestinationAddress $model)
    {
        $this->resource->delete($model);
    }

    /**
     * @param $orderId
     * @return array
     * @throws \Exception
     */
    public function getDestAddressByOrderId($orderId): array
    {
        try {
            $select = $this->connection->select()
                ->from(['a' => DestinationAddressResource::TABLE_NAME])
                ->where('a.order_id = ?', $orderId)
                ->join(
                    ['l' => Warehouse::TABLE_NAME],
                    'l.warehouse_id = a.warehouse_id',
                    ['warehouse_desc']
                )
                ->join(
                    ['c' => City::TABLE_NAME],
                    'c.city_id = a.city_id',
                    ['city_name']
                );
        } catch (\Exception $e) {
            $this->rollBack();
            throw $e;
        }

        return $this->connection->fetchRow($select);
    }

    /**
     * @param  $destinationData
     * @return bool
     * @throws \Exception
     */
    public function saveDestAddress($destinationData): bool
    {
        try {
            $this->connection->insertMultiple(DestinationAddressResource::TABLE_NAME, $destinationData);
        } catch (\Exception $e) {
            throw $e;
        }

        return true;
    }
}
