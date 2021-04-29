<?php
/**
* Impact: Partnership Cloud for Magento
*
* @package     Impact_Itegration
* @copyright   Copyright (c) 2021 Impact. (https://impact.com)
*/

namespace Impact\Integration\Setup;

use Impact\Integration\Service\ImpactApiService; 
use Magento\Integration\Api\IntegrationServiceInterface;
use Magento\Integration\Api\OauthServiceInterface;
use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Class Uninstall
 *
 * @package Impact\Integration\Setup
 */
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

    /**
     * @var setup
     */
    private $setup;

    /**
     * Uninstall constructor.
     * 
     * @param Config $Config
     * @param IntegrationServiceInterface $integrationService
     * @param OauthServiceInterface $oauthService
     * @param ModuleDataSetupInterface $setup
     */
    public function __construct(
        Config $Config,
        IntegrationServiceInterface $integrationService,
        OauthServiceInterface $oauthService,
        ModuleDataSetupInterface $setup
    )
    {
        $this->_resourceConfig = $resourceConfig;
        $this->_integrationService = $integrationService;
        $this->oauthService = $oauthService;
        $this->setup = $setup;
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
            $impactApiService = new ImpactApiService($accessToken, static::API_ENDPOINT_UNINSTALL , 'DELETE', json_encode(['Deleted'=>'si']));
            $response = $impactApiService->execute();
            
            /**
             * Update the Head and Style in html head
             */
            // Get the current UTT            
            $connection = $this->setup->getConnection();
            $select = $connection->select()
                                ->from('core_config_data')
                                ->where($connection->quoteIdentifier('path') . "= 'impact_integration/existing_customer/utt_default'");
            $rowCurrentUTT = $connection->fetchRow($select);
            $currentUTT = "";
            if ($rowCurrentUTT) {
                $currentUTT = $rowCurrentUTT['value'];
            }
           
            // Get the Head and Style in html head
            $select = $connection->select()
                                ->from('core_config_data')
                                ->where($connection->quoteIdentifier('path') . "= 'design/head/includes'");
            $rowHeadHTML = $connection->fetchRow($select);
            $headHTML = "";
            if ($rowHeadHTML) {
                $headHTML = $rowHeadHTML['value'];
            }

            // Update the Head and Style in html head
            $headHTMLWithOutUTT = str_replace($currentUTT, "", $headHTML);
            // Insert core data
            $this->_resourceConfig->saveConfig(
                'design/head/includes',
                $headHTMLWithOutUTT,
                'stores',
                1
            );

            // Delete data on database
            $this->deleteImpactData();
       }
    }

    /**
     * Delete data on database
     * 
     * @return void
     */
    private function deleteImpactData():void
    {
        $this->_resourceConfig->deleteConfig(
            'impact_integration/existing_customer/account_sid',
            'default',
            0
        );
        $this->_resourceConfig->deleteConfig(
            'impact_integration/existing_customer/auth_token',
            'default',
            0
        );
        $this->_resourceConfig->deleteConfig(
            'impact_integration/existing_customer/program_id',
            'default',
            0
        );
        $this->_resourceConfig->deleteConfig(
            'impact_integration/existing_customer/event_type_id',
            'default',
            0
        );
        $this->_resourceConfig->deleteConfig(
            'impact_integration/existing_customer/universal_tracking_tag',
            'default',
            0
        );
        $this->_resourceConfig->deleteConfig(
            'impact_integration/existing_customer/conversion_url',
            'default',
            0
        );
        $this->_resourceConfig->deleteConfig(
            'impact_integration/existing_customer/refund_url',
            'default',
            0
        );
        $this->_resourceConfig->deleteConfig(
            'impact_integration/existing_customer/utt_default',
            'default',
            0
        );
        $this->_resourceConfig->deleteConfig(
            'impact_integration/general/enabled',
            'default',
            0
        );
    } 
}