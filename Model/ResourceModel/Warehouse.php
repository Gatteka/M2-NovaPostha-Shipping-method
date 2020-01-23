<?php

namespace Learn\NovaPoshta\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Warehouse
 * @package Learn\NovaPoshta\Model\ResourceModel
 */
class Warehouse extends AbstractDb
{
    const TABLE_NAME = 'custom_novaposhta_warehouses';

    const TABLE_ID = 'warehouse_id';
    /**
     * Define main table and initialize connection
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, self::TABLE_ID);
    }
}
