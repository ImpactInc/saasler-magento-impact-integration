<?php

/**
 * Impact: Partnership Cloud for Magento
 *
 * @package     Impact_Integration
 * @copyright   Copyright (c) 2021 Impact. (https://impact.com)
 */

namespace Impact\Integration\Setup;

use Impact\Integration\Service\ImpactApiService;
use Magento\Integration\Api\IntegrationServiceInterface;
use Magento\Integration\Api\OauthServiceInterface;
use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Impact\Integration\Model\ConfigData;

/**
 * Class Uninstall - Uninstall module
 *
 */
class Uninstall implements \Magento\Framework\Setup\UninstallInterface
{
    /**
     * API request endpoint integration
     */
    protected const API_ENDPOINT_UNINSTALL = 'https://saasler-magento-impact.herokuapp.com/uninstall';

    /**
     *
     *
     * @var \Magento\Integration\Api\IntegrationServiceInterface
     */
    private $_integrationService;

    /**
     * Oauth Service Interface
     *
     * @var Magento\Integration\Api\OauthServiceInterface
     */
    private $oauthService;

    /**
     * @var \Magento\Config\Model\ResourceModel\Config
     */
    protected $_resourceConfig;

    /**
     * @var setup
     */
    private $setup;

    /**
     *
     * @var Impact\Integration\Model\ConfigData
     */
    private $configData;

    /**
     * Uninstall constructor.
     *
     * @param Config $Config
     * @param IntegrationServiceInterface $integrationService
     * @param OauthServiceInterface $oauthService
     * @param ModuleDataSetupInterface $setup
     * @param ConfigData $configData
     */
    public function __construct(
        Config $Config,
        IntegrationServiceInterface $integrationService,
        OauthServiceInterface $oauthService,
        ModuleDataSetupInterface $setup,
        ConfigData $configData
    ) {
        $this->_resourceConfig = $Config;
        $this->_integrationService = $integrationService;
        $this->oauthService = $oauthService;
        $this->setup = $setup;
        $this->configData = $configData;
    }

    /**
     * Module uninstall code
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     * @return void
     */
    public function uninstall(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        // Validate if the integration was enabled
        $integration = $this->_integrationService->findByName('ImpactIntegration');
        if (isset($integration) && $integration->getStatus()) {
            // Get accesstoken from integration
            $token = $this->oauthService->getAccessToken($integration->getConsumerId());
            $accessToken = $token->getToken();

            // Send request uninstall in saasler
            $impactApiService = new ImpactApiService(
                $accessToken,
                static::API_ENDPOINT_UNINSTALL,
                'DELETE',
                json_encode(['Deleted' => 'yes'])
            );
            $response = $impactApiService->execute();

            $installer = $setup;
            $installer->startSetup();

            // Delete integration record
            $integration->delete();

            // Delete Impact Credentials
            $this->configData->deleteImpactIntegrationConfigData();

            $installer->endSetup();
        }
    }
}
