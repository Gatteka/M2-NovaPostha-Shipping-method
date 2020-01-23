<?php

namespace Learn\NovaPoshta\Model\ResourceModel\City;

use Learn\NovaPoshta\Model\City;
use Learn\NovaPoshta\Model\ResourceModel\City as Resource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Learn\NovaPoshta\Model\ResourceModel\Warehouse
 */
class Collection extends AbstractCollection
{
    public $_idFieldName = 'city_id';

    protected function _construct()
    {
        $this->_init(City::class, Resource::class);
    }
}
