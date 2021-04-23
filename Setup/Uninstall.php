<?php

namespace Impact\ImpactIntegration\Setup;

use Impact\ImpactIntegration\Service\ImpactApiService; 

class Uninstall implements \Magento\Framework\Setup\UninstallInterface
{
    /**
     * @var \Magento\Config\Model\ResourceModel\Config
     */
    protected $_resourceConfig;

    public function __construct(\Magento\Config\Model\ResourceModel\Config $resourceConfig)
    {
        $this->_resourceConfig = $resourceConfig;
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
        $impactApiService = new ImpactApiService('', static::API_ENDPOINT_UNINSTALL , 'DELETE', $body);
        $response = $impactApiService->execute();
        $this->deleteImpactData();

        $setup->startSetup();

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
}