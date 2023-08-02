<?php

/**
 * Impact: Partnership Cloud for Magento
 *
 * @package     Impact_Integration
 * @copyright   Copyright (c) 2021 Impact. (https://impact.com)
 */

namespace Impact\Integration\Block\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Backend\Block\Widget\Button as ButtonBlock;

/**
 * Class Button - Button controller
 *
 */
class Button extends Field
{
    /**
     * @var string $_template
     */
    protected $_template = 'Impact_Integration::system/config/button.phtml';

    /**
     * Function render.
     *
     * @param Magento\Framework\Data\Form\Element\AbstractElement $element
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    /**
     * Function _getElementHtml.
     *
     * @param Magento\Framework\Data\Form\Element\AbstractElement $element
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->_toHtml();
    }

    /**
     * Function getAjaxUrl.
     */
    public function getAjaxUrl()
    {
        return $this->getUrl('Impact_Integration/system_config/button');
    }

    /**
     * Function getButtonHtml.
     */
    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock(ButtonBlock::class)
        ->setData(
            [
                'id' => 'btnid',
                'class' => 'primary',
                'label' => __('Uninstall'),
            ]
        );

        return $button->toHtml();
    }
}
