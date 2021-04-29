<?php
/**
* Impact: Partnership Cloud for Magento
*
* @package     Impact_Itegration
* @copyright   Copyright (c) 2021 Impact. (https://impact.com)
*/

namespace Impact\Integration\Helper;

use Magento\Store\Model\ScopeInterface;
use \Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class Data
 *
 * @package Impact\Integration\Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
    * Row in core_config_data table for enable extension
    */
    const MODULE_ENABLE_DISABLE = 'impact_integration/general/enabled';

    /**
     * Data constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Set Store scope.
     * 
     * @return string
     */
    public function setStoreScope()
    {
        return ScopeInterface::SCOPE_STORE;
    }

    /**
     * get if store is enabled.
     * 
     * @return integer
     */
    public function isEnabled()
    {
        return $this->scopeConfig->getValue(static::MODULE_ENABLE_DISABLE, $this->setStoreScope());
    }
}