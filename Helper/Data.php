<?php
/**
* Impact: Partnership Cloud for Magento
*
* @package     Impact_Itegration
* @copyright   Copyright (c) 2021 Impact. (https://impact.com)
*/

namespace Impact\Integration\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use \Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class Data
 *
 * @package Impact\Integration\Helper
 */
class Data extends AbstractHelper
{
    /**
    * Row in core_config_data table for enable extension
    */
    const MODULE_ENABLE_DISABLE = 'impact_integration/general/enabled';

    const  XML_CONVERSION_URL_PATH = 'impact_integration/existing_customer/conversion_url';

    const XML_REFUND_URL_PATH = 'impact_integration/existing_customer/refund_url';

    const XML_DESIGN_HEAD_INCLUDES_PATH = 'design/head/includes';

    const XML_UTT_DEFAULT_PATH = 'impact_integration/existing_customer/utt_default';

    public function getConversionUrl(): string
    {
        return $this->scopeConfig->getValue(static::XML_CONVERSION_URL_PATH);
    }

    public function getRefundUrl(): string
    {
        return $this->scopeConfig->getValue(static::XML_REFUND_URL_PATH);
    }

    public function getDesignHeadIncludes(): string
    {
        return $this->scopeConfig->getValue(static::XML_DESIGN_HEAD_INCLUDES_PATH);
    }

    public function getUttDefault(): string
    {
        return $this->scopeConfig->getValue(static::XML_UTT_DEFAULT_PATH);
    }

    public function getConfigValue($path)
    {
        return $this->scopeConfig->getValue($path);
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