<?php

namespace Learn\NovaPoshta\Block\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class UpdateWarehousesButton
 * @package Learn\NovaPoshta\Block\System\Config
 */
class UpdateWarehousesButton extends Field
{
    /**
     * @var string
     */
    protected $_template = 'Learn_NovaPoshta::buttons/updateWarehousesButton.phtml';

    const URL = 'update_warehouses/update/updateWarehouses';

    /**
     * UpdateWarehousesButton constructor.
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }

    /**
     * @return string
     */
    public function getAjaxUrl()
    {
        return $this->getUrl(self::URL);
    }

    /**
     * @return mixed
     * @throws LocalizedException
     */
    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Button'
        )->setData(
            [
                'id' => 'update_warehouses_button_1',
                'label' => __('Update data'),
                'onclick' => 'updateWarehousesData()',
            ]
        );
        return $button->toHtml();
    }
}
