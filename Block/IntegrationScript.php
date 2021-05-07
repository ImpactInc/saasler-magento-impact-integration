<?php


namespace Impact\Integration\Block;

use Impact\Integration\Helper\Data;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class IntegrationScript extends Template
{
    protected Data $helperData;

    public function __construct(
        Context $context,
        Data $helperData
    ) {

        $this->helperData = $helperData;

        parent::__construct($context);
    }

    public function isActive(): bool
    {
        return true;
    }
}