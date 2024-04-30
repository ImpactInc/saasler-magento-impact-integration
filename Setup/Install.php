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
     * Store manager instance
     *
     * This property holds the store manager which provides functionality to manage
     * stores in Magento.
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ConfigBasedIntegrationManager
     */
    private $integrationManager;

    /**
     * @param ConfigBasedIntegrationManager $integrationManager
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(ConfigBasedIntegrationManager $integrationManager, StoreManagerInterface $storeManager)
    {
        $this->integrationManager = $integrationManager;
        $this->storeManager = $storeManager;
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
            json_encode(['store_base_url' => $this->storeManager->getStore()->getBaseUrl()])
        );
        $response = $impactApiService->execute();
    }
}
