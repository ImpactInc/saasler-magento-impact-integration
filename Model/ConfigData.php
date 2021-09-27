<?php
/**
* Impact: Partnership Cloud for Magento
*
* @package     Impact_Itegration
* @copyright   Copyright (c) 2021 Impact. (https://impact.com)
*/

namespace impact_tech\module-magento-integration\Model;

use impact_tech\module-magento-integration\Helper\Data;
use Magento\Config\Model\ResourceModel\Config;


/**
 * Class ConfigData
 *
 * @package impact_tech\module-magento-integration\Model
 */
class ConfigData
{
    /**
     * @var \Magento\Config\Model\ResourceModel\Config
     */
    protected $_resourceConfig;

    /**
     * @var impact_tech\module-magento-integration\Helper\Data
     */
    protected $helper;

    /**
     * @var array $configKeys
     */
    protected $configKeys = [
        'impact_integration/existing_customer/conversion_url',
        'impact_integration/existing_customer/refund_url',
        'impact_integration/existing_customer/utt_default'
    ];

    /**
     * @var array $configImpactIntegrationKeys
     */
    protected $configImpactIntegrationKeys = [
        'impact_integration/existing_customer/account_sid',
        'impact_integration/existing_customer/auth_token',
        'impact_integration/existing_customer/program_id',
        'impact_integration/existing_customer/event_type_id',
        'impact_integration/existing_customer/universal_tracking_tag',
        'impact_integration/existing_customer/conversion_url',
        'impact_integration/existing_customer/refund_url',
        'impact_integration/existing_customer/utt_default',
        'impact_integration/general/enabled'
    ];

    /**
     * ConfigData constructor.
     * 
     * @param Config $resourceConfig
     * @param Data $helper
     */
    public function __construct(Config $resourceConfig, Data $helper)
    {
        $this->_resourceConfig = $resourceConfig;

        $this->helper = $helper;
    }

    /**
     * Function to refresh Urls .
     * 
     * @param Array $urls
     */
    public function refresh($urls)
    {
        $this->cleanExistingConfigData();

        $toUpdate = [
            'impact_integration/existing_customer/conversion_url' => $urls['conversion_url'],
            'impact_integration/existing_customer/refund_url' => $urls['refund_url'],
            'impact_integration/existing_customer/utt_default' => $urls['utt_default']
        ];

        foreach ($toUpdate as $key => $value) {
            $this->_resourceConfig->saveConfig($key, $value, 'default', 0);
        }

    }

    /**
     * Function to clean Urls 
     */
    protected function cleanExistingConfigData()
    {
        foreach ($this->configKeys as $configKey) {
            $value = $this->helper->getConfigValue($configKey);
            if ($value) {
                $this->_resourceConfig->deleteConfig($configKey, 'default', 0);
            }
        }
    }

    /**
     * Function to delete Impact settings .
     */
    public function deleteImpactIntegrationConfigData()
    {
        foreach ($this->configImpactIntegrationKeys as $configImpactKey) {
            $this->_resourceConfig->deleteConfig($configImpactKey, 'default', 0);
        }
    }
}