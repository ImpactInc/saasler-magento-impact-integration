<?php
/**
* Impact: Partnership Cloud for Magento
*
* @package     Impact_Integration
* @copyright   Copyright (c) 2021 Impact. (https://impact.com)
*/

namespace Impact\Integration\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

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

    /**
    * Row in core_config_data table for conversion url
    */
    const  XML_CONVERSION_URL_PATH = 'impact_integration/existing_customer/conversion_url';

    /**
    * Row in core_config_data table for refund url
    */
    const XML_REFUND_URL_PATH = 'impact_integration/existing_customer/refund_url';

    /**
    * Row in core_config_data table for utt script
    */
    const XML_UTT_DEFAULT_PATH = 'impact_integration/existing_customer/utt_default';

     /**
     * Get conversion url.
     * 
     * @return string
     */
    public function getConversionUrl()
    {
        return $this->scopeConfig->getValue(static::XML_CONVERSION_URL_PATH);
    }

    /**
     * Get refund url.
     * 
     * @return string
     */
    public function getRefundUrl()
    {
        return $this->scopeConfig->getValue(static::XML_REFUND_URL_PATH);
    }

    /**
     * Get utt defaul.
     * 
     * @return string
     */
    public function getUttDefault()
    {
        return $this->scopeConfig->getValue(static::XML_UTT_DEFAULT_PATH);
    }

    /**
     * Get Config Value.
     * 
     * @param string $path
     * @return string
     */
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
     * Get if store is enabled.
     * 
     * @return integer
     */
    public function isEnabled()
    {
        return $this->scopeConfig->getValue(static::MODULE_ENABLE_DISABLE, $this->setStoreScope());
    }
}