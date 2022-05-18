<?php
/**
* Impact: Partnership Cloud for Magento
*
* @package     Impact_Integration
* @copyright   Copyright (c) 2021 Impact. (https://impact.com)
*/

namespace Impact\Integration\Controller\Adminhtml\System\Config;
 
use Impact\Integration\Service\ImpactApiService; 
use Magento\Integration\Api\IntegrationServiceInterface;
use Magento\Integration\Api\OauthServiceInterface;
use Impact\Integration\Model\ConfigData;
use Psr\Log\LoggerInterface;
use Magento\Backend\App\Action\Context;
use Magento\Backend\App\Action;

/**
 * Class Button
 *
 * @package Impact\Integration\Controller\Adminhtml\System\Config
 */
class Button extends Action
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
     *
     * @var Impact\Integration\Model\ConfigData
     */
    private $configData;

    /**
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * Button constructor.
     * 
     * @param Context $context,
     * @param LoggerInterface $logger
     * @param IntegrationServiceInterface $integrationService
     * @param OauthServiceInterface $oauthService
     * @param ConfigData $configData
     */
    public function __construct(
        Context $context,
        LoggerInterface $logger,
        IntegrationServiceInterface $integrationService,
        OauthServiceInterface $oauthService,
        ConfigData $configData

    ) {
        $this->_logger = $logger;
        $this->_integrationService = $integrationService;
        $this->oauthService = $oauthService;
        $this->configData = $configData;
        parent::__construct($context);
    }

    /**
     * Execute code
     *
     * @return void
     */
    public function execute()
    {

        $this->_logger->info('Start delete all');
        // Validate if the integration was enabled
        $integration = $this->_integrationService->findByName('ImpactIntegration');
        if (isset($integration) && $integration->getStatus()) {
            // Get accesstoken from integration
            $token = $this->oauthService->getAccessToken($integration->getConsumerId());
            $accessToken = $token->getToken();
        
            // Send request uninstall in saasler
            $impactApiService = new ImpactApiService($accessToken, static::API_ENDPOINT_UNINSTALL , 'DELETE', json_encode(['Deleted'=>'yes']));
            $response = $impactApiService->execute();

            // Delete integration record
            $integration->delete();

            // Delete Impact Credentials
            $this->configData->deleteImpactIntegrationConfigData();
        }
    }
}