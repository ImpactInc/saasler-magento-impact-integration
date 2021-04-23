<?php

namespace Impact\ImpactIntegration\Helper;

use Magento\Store\Model\ScopeInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    const MODULE_ENABLE_DISABLE = 'impact_impactintegration/general/enabled';

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    public function setStoreScope()
    {
        return ScopeInterface::SCOPE_STORE;
    }
    public function isEnabled()
    {
        return $this->scopeConfig->getValue(static::MODULE_ENABLE_DISABLE, $this->setStoreScope());
    }
}