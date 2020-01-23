<?php

namespace Learn\NovaPoshta\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class City
 * @package Learn\NovaPoshta\Model\ResourceModel
 */
class City extends AbstractDb
{
    const TABLE_NAME = 'custom_novaposhta_cities';

    const TABLE_ID = 'city_id';
    /**
     * Define main table and initialize connection
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, self::TABLE_ID);
        $this->_isPkAutoIncrement = false;
    }
}
