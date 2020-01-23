<?php

namespace Learn\NovaPoshta\Block\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Exception\LocalizedException;

class CalculateDeliveryCostButton extends Field
{
    /**
     * @var string
     */
    protected $_template = 'Learn_NovaPoshta::buttons/calculateDeliveryCostButton.phtml';

    const URL = 'update_warehouses/calculate/calculateDeliveryCost';

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
                'id' => 'calculate_delivery_button_1',
                'label' => __('Calculate Delivery Cost'),
                'onclick' => 'calculate()',
            ]
        );
        return $button->toHtml();
    }
}
