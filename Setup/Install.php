<?php
/**
 * Impact: Partnership Cloud for Magento
 *
 * @package     Impact_Integration
 * @copyright   Copyright (c) 2021 Impact. (https://impact.com)
 */

namespace Impact\Integration\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Integration\Model\ConfigBasedIntegrationManager;
use Impact\Integration\Service\ImpactApiService;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class InstallData
 *
 */
class Install
{
    /**
     * API request endpoint install
     */
    protected const API_ENDPOINT_INSTALL = 'https://magento-integration.impact.com/webhooks/installation_notifications';

    /**
     * @var \Magento\Store\Model\StoreManagerInterface $_storeManager
     */

    /**
     * @var ConfigBasedIntegrationManager
     */

    private $integrationManager;

    /**
     * @param ConfigBasedIntegrationManager $integrationManager
     * @param StoreManagerInterface $_storeManager
     */
    public function __construct(ConfigBasedIntegrationManager $integrationManager, StoreManagerInterface $_storeManager)
    {
        $this->integrationManager = $integrationManager;
        $this->_storeManager = $_storeManager;
    }

    /**
     * @inheritdoc
     */

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->integrationManager->processIntegrationConfig(['ImpactIntegration']);
        // Send request uninstall in saasler
        $impactApiService = new ImpactApiService(
            '',
            static::API_ENDPOINT_INSTALL,
            'POST',
            json_encode(['store_base_url'=>$this->_storeManager->getStore()->getBaseUrl()])
        );
        $response = $impactApiService->execute();
    }
}
