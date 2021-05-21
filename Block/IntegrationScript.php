<?php
/**
* Impact: Partnership Cloud for Magento
*
* @package     Impact_Itegration
* @copyright   Copyright (c) 2021 Impact. (https://impact.com)
*/

namespace Impact\Integration\Block;

use Impact\Integration\Helper\Data;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class IntegrationScript
 *
 * @package Impact\Integration\Block
 */
class IntegrationScript extends Template
{
    /**
     * @var Data $helperData
     */
    protected $helperData;

    /**
     * IntegrationScript constructor.
     * 
     * @param Context $context
     * @param Data $helperData
     */
    public function __construct(
        Context $context,
        Data $helperData
    ) {

        $this->helperData = $helperData;

        parent::__construct($context);
    }

    /**
     * Function isActive 
     *
     * @return bool
     */
    public function isActive(): bool
    {
        /**
         * @TODO: Check if the module is active to show the scripts in the header.
         */
        return $this->helperData->isEnabled();
    }

    /**
     * Function getScript 
     *
     * @return string|null
     */
    public function getScript()
    {
        return $this->helperData->getUttDefault();
    }
}