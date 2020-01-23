<?php


namespace Learn\NovaPoshta\Model\ResourceModel\Warehouse;


use Learn\NovaPoshta\Model\ResourceModel\Warehouse as Resource;
use Learn\NovaPoshta\Model\Warehouse;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Learn\NovaPoshta\Model\ResourceModel\Warehouse
 */
class Collection extends AbstractCollection
{
    public $_idFieldName = 'warehouse_id';

    protected function _construct()
    {
        $this->_init(Warehouse::class, Resource::class);
    }
}