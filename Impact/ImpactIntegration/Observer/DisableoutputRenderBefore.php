<?php
namespace Impact\ImpactIntegration\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Impact\ImpactIntegration\Service\ImpactApiService; 
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Stdlib\CookieManagerInterface;

class DisableoutputRenderBefore implements ObserverInterface
{   
    /**
     * API request endpoint integration
     */
    const API_ENDPOINT_UNINSTALL = 'https://saasler-magento-impact.herokuapp.com/uninstall';
    private $setup;
    /**
     * @var CookieManagerInterface
     */
    protected $cookieManager;

    /**
     * @var \Magento\Config\Model\ResourceModel\Config
     */
    protected $_resourceConfig;
   
    public function __construct(ModuleDataSetupInterface $setup, CookieManagerInterface $cookieManager, \Magento\Config\Model\ResourceModel\Config $resourceConfig)
    {
        $this->setup = $setup;
        $this->cookieManager = $cookieManager;
        $this->_resourceConfig = $resourceConfig;
    }

    /**
    * @param EventObserver $observer
    * @return $this
    */
    public function execute(EventObserver $observer)
    {
        // Send data with ImpactApiService class to delete store
        \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->info('Deshabilitó la extension');
        $body = '';
        $impactApiService = new ImpactApiService('', static::API_ENDPOINT_UNINSTALL , 'DELETE', $body);
        $response = $impactApiService->execute();
        $this->deleteImpactData();
        return $this;
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