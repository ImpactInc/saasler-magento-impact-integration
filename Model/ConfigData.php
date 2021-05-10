<?php

namespace Impact\Integration\Model;

use Impact\Integration\Helper\Data;
use Magento\Config\Model\ResourceModel\Config;

class ConfigData
{
    protected Config $_resourceConfig;

    protected Data $helper;

    protected array $configKeys = [
        'impact_integration/existing_customer/conversion_url',
        'impact_integration/existing_customer/refund_url',
//        'impact_integration/existing_customer/utt_default'
    ];

    public function __construct(Config $resourceConfig, Data $helper)
    {
        $this->_resourceConfig = $resourceConfig;

        $this->helper = $helper;
    }

    public function refresh($urls)
    {
        $this->cleanExistingConfigData();

        $toUpdate = [
            'impact_integration/existing_customer/conversion_url' => $urls['conversion_url'],
            'impact_integration/existing_customer/refund_url' => $urls['refund_url'],
//            'impact_integration/existing_customer/utt_default' => $urls['utt_default']
        ];

        foreach ($toUpdate as $key => $value) {
            $this->_resourceConfig->saveConfig($key, $value, 'default', 0);
        }

    }

    protected function cleanExistingConfigData()
    {
        foreach ($this->configKeys as $configKey) {
            $value = $this->helper->getConfigValue($configKey);
            if ($value) {
                $this->_resourceConfig->deleteConfig($configKey, 'default', 0);
            }
        }
    }
}