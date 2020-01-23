<?php

namespace Learn\NovaPoshta\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class DestinationAddress
 * @package Learn\NovaPoshta\Model\ResourceModel
 */
class DestinationAddress extends AbstractDb
{
    const TABLE_NAME = 'novaposhta_order_destination_address';

    const TABLE_ID = 'order_id';

    /**
     * Define main table and initialize connection
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, self::TABLE_ID);
        $this->_isPkAutoIncrement = true;
    }
}
