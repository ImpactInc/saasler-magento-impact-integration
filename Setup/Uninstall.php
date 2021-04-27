<?php

namespace impact\impactintegration\Setup;

use impact\impactintegration\Service\ImpactApiService; 
use Magento\Integration\Api\IntegrationServiceInterface;
use Magento\Integration\Api\OauthServiceInterface;

class Uninstall implements \Magento\Framework\Setup\UninstallInterface
{
     /**
     * API request endpoint integration
     */
    const API_ENDPOINT_UNINSTALL = 'https://saasler-magento-impact.herokuapp.com/uninstall';

     /**
     * Integration service
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

    public function __construct(
        \Magento\Config\Model\ResourceModel\Config $resourceConfig,
        IntegrationServiceInterface $integrationService,
        OauthServiceInterface $oauthService
    )
    {
        $this->_resourceConfig = $resourceConfig;
        $this->_integrationService = $integrationService;
        $this->oauthService = $oauthService;
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
        $body = '';
        // Get accesstoken from integration
        $accessToken = $this->getAccessToken();     // Validar si el usuario activo la integracion
        $impactApiService = new ImpactApiService($accessToken, static::API_ENDPOINT_UNINSTALL , 'DELETE', json_encode(['deleted' => 'Si']));
        $response = $impactApiService->execute();
        $this->deleteImpactData();

        $setup->startSetup();

        \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->info('Se desinstalo impact-extension');
        // Uninstall logic here

        $setup->endSetup();
    }

    /**
     * Delete data on database
     */
    private function deleteImpactData():void
    {
        $this->_resourceConfig->deleteConfig(
            'impact_impactintegration/existing_customer/account_sid',
            'default',
            0
        );
        $this->_resourceConfig->deleteConfig(
            'impact_impactintegration/existing_customer/auth_token',
            'default',
            0
        );
        $this->_resourceConfig->deleteConfig(
            'impact_impactintegration/existing_customer/program_id',
            'default',
            0
        );
        $this->_resourceConfig->deleteConfig(
            'impact_impactintegration/existing_customer/event_type_id',
            'default',
            0
        );
        $this->_resourceConfig->deleteConfig(
            'impact_impactintegration/existing_customer/universal_tracking_tag',
            'default',
            0
        );
        $this->_resourceConfig->deleteConfig(
            'impact_impactintegration/existing_customer/conversion_url',
            'default',
            0
        );
        $this->_resourceConfig->deleteConfig(
            'impact_impactintegration/existing_customer/refund_url',
            'default',
            0
        );
        // design/head/includes

        /*$this->_resourceConfig->deleteConfig(
            'impact_impactintegration/existing_customer/utt_default',
            'default',
            0
        );*/
        
        
    } 

    /**
     * Get access token from Impact Integration
     * 
     * @return String
     */
    private function getAccessToken()
    {
        $accessToken = '';
        // Get the Impact integration data
        $integration = $this->_integrationService->findByName('impactintegration');
        if (!$integration->getId()) {
            throw new NoSuchEntityException(__('Cannot find Impact integration.'));
        }
        $consumerId = $integration->getConsumerId();
        $token = $this->oauthService->getAccessToken($consumerId);
        $accessToken = $token->getToken();
        return $accessToken;
    }
}