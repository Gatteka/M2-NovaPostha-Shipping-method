<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.06.18
 * Time: 12:47
 */

namespace Learn\NovaPoshta\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Class Warehouse
 * @package Learn\NovaPoshta\Model
 */
class Warehouse extends AbstractModel
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\Warehouse::class);
    }
}
