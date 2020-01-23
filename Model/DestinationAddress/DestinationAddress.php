<?php

namespace Learn\NovaPoshta\Model\DestinationAddress;

use Learn\NovaPoshta\Model\ResourceModel\DestinationAddress as Resource;
use Magento\Framework\Model\AbstractModel;

/**
 * Class DestinationAddress
 * @package Learn\NovaPoshta\Model\DestinationAddress
 */
class DestinationAddress extends AbstractModel
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Resource::class);
    }

}