<?php

namespace impact\impactintegration\Service;

use Magento\Integration\Api\IntegrationServiceInterface;
use Magento\Config\Model\Config as SystemConfig;
use Magento\Integration\Api\OauthServiceInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class ImpactAuthService
 */
class ImpactAuthService
{
    /**
     * Integration service
     *
     * @var \Magento\Integration\Api\IntegrationServiceInterface
     */
    private $_integrationService;

    /**
     * Model Config
     *
     * @var Magento\Config\Model\Config
     */
    private $config;

    /**
     * Oauth Service Interface
     *
     * @var Magento\Integration\Api\OauthServiceInterface
     */
    private $oauthService;
    
    /**
     * ImpactAuthService constructor
     *
     * @param string $endpoint
     */
    public function __construct(
        IntegrationServiceInterface $integrationService, 
        SystemConfig $config, 
        OauthServiceInterface $oauthService
    )
    {
        $this->config = $config;
        $this->oauthService = $oauthService;
        $this->_integrationService = $integrationService;
    }

    /**
     * Execute 
     */
    public function execute()
    {
        return $this->getAccessToken();
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
        $accessToken = $token->getSecret();
        return $accessToken;
    }
}