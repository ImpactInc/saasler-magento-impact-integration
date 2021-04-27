<?php
namespace impact\impactintegration\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\App\RequestInterface;
use impact\impactintegration\Service\ImpactApiService; 
use Magento\Integration\Api\IntegrationServiceInterface;
use Magento\Integration\Api\OauthServiceInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use impact\impactintegration\Helper\Data;
use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Cache\Frontend\Pool;

class ConfigChange implements ObserverInterface
{
    /**
     * API request endpoint integration
     */
    const API_ENDPOINT_INTEGRATION = 'https://saasler-magento-impact.herokuapp.com/integration_setting';

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
     * @var \Magento\Config\Model\ResourceModel\Config
     */
    protected $_resourceConfig;

    /**
     * Oauth Service Interface
     *
     * @var Magento\Integration\Api\OauthServiceInterface
     */
    private $oauthService;

    private $request;
    private $setup;
    private $helper; 

    public function __construct(
        RequestInterface $request, 
        IntegrationServiceInterface $integrationService, 
        Config $resourceConfig, 
        OauthServiceInterface $oauthService, 
        ModuleDataSetupInterface $setup,
        TypeListInterface $cacheTypeList,
        Pool $cacheFrontendPool,
        Data $helper)
    {
        $this->_resourceConfig = $resourceConfig;
        $this->oauthService = $oauthService;
        $this->_integrationService = $integrationService;
        $this->setup = $setup;
        $this->request = $request;
        $this->cacheTypeList = $cacheTypeList;
        $this->cacheFrontendPool = $cacheFrontendPool;
        $this->helper = $helper;
    }
    public function execute(EventObserver $observer)
    {
        // First get the current UTT before update
        $oldUTT = '';
        $rowCurrentUTT = $this->validateSettingValue('utt_default');
        if ($rowCurrentUTT) {
            $oldUTT = $rowCurrentUTT['value'];
        }
        // Get the Head and Style in html head
        $rowConfig = $this->getCoreCofigValue('design/head/includes');
        
        // Validate if module is enable
        if ($this->helper->isEnabled()) {
            // Saasler Script
            //$saasler_script = '<script type="text/javascript"> !function(){!function(){const e=function(e,n){n||(n=window.location.href),e=e.replace(/[\[\]]/g,"\\$&");var c=new RegExp("[?&]"+e+"(=([^&#]*)|&|#|$)").exec(n);return c?c[2]?decodeURIComponent(c[2].replace(/\+/g," ")):"":null}("irclickid");e&&function(e,n,c){const i=new Date;i.setTime(i.getTime()+24*c*60*60*1e3);const o="expires="+i.toUTCString();document.cookie=e+"="+n+";SameSite=None;"+o+";path=/"}("irclickid",e,30)}()}();</script>';
            $saasler_script = '<script type="text/javascript"> !function(){!function(){const e=function(e,n){n||(n=window.location.href),e=e.replace(/[\[\]]/g,"\\$&");var c=new RegExp("[?&]"+e+"(=([^&#]*)|&|#|$)").exec(n);return c?c[2]?decodeURIComponent(c[2].replace(/\+/g," ")):"":null}("irclickid");console.log("irclickid",e),e&&function(e,n,c){const i=new Date;i.setTime(i.getTime()+24*c*60*60*1e3),i.toUTCString(),document.cookie=e+"="+n+";"}("irclickid",e,30)}()}();</script>';
            $ircClickFunction = ' <script type="text/javascript> function setCookie(e,i,t){const o=new Date;o.setTime(o.getTime()+24*t*60*60*1e3);const n="expires="+o.toUTCString();document.cookie=e+"="+i+";SameSite=None;"+n+";path=/"}ire("generateClickId",function(e){setCookie("irclickid",e,30)}); </script> ';
            // Get credentials from Impact Setting form
            $params = $this->request->getParam('groups');
            $account_sid = $params['existing_customer']['fields']['account_sid']['value'] ? $params['existing_customer']['fields']['account_sid']['value'] : '';
            $auth_token = $params['existing_customer']['fields']['auth_token']['value'] ? $params['existing_customer']['fields']['auth_token']['value'] : '';
            $program_id = $params['existing_customer']['fields']['program_id']['value'] ? $params['existing_customer']['fields']['program_id']['value'] : '';
            $event_type_id = $params['existing_customer']['fields']['event_type_id']['value'] ? $params['existing_customer']['fields']['event_type_id']['value'] : '';
            $universal_tracking_tag = $params['existing_customer']['fields']['universal_tracking_tag']['value'] ? $params['existing_customer']['fields']['universal_tracking_tag']['value'] : '';
            $universal_tracking_tag = str_replace(", {customerid: '' /*INSERT CUSTOMER ID*/, customeremail: '' /*INSERT SHA1 HASHED CUSTOMER EMAIL*/}", "", $universal_tracking_tag);
            $credentials = [
                'username' => $account_sid,
                'password' => $auth_token,
                'campaign_id' => $program_id,
                'action_tracker_id' => $event_type_id
            ];
            $parameters = ['integration_setting' => $credentials];
            $body = json_encode($parameters);
            // Get accesstoken from integration
            $accessToken = $this->getAccessToken();

            // Send data with ImpactApiService class
            $impactApiService = new ImpactApiService($accessToken, static::API_ENDPOINT_INTEGRATION , 'PUT', $body);
            $response = $impactApiService->execute();

            // Get response with conversion and refund url
            $responseBody = $response->getBody();
            $responseContent = $responseBody->getContents();
            $urls = json_decode($responseContent, true);

            // Validate conversion url
            $rowConversion = $this->validateSettingValue('conversion_url');
            if ($rowConversion) {
                $this->_resourceConfig->deleteConfig(
                    'impact_impactintegration/existing_customer/conversion_url',
                    'default',
                    0
                );
            }
            // Insert conversion url
            $this->_resourceConfig->saveConfig(
                'impact_impactintegration/existing_customer/conversion_url',
                $urls['conversion_url'],
                'default',
                0
            );
                
            // Validate refund url
            $rowRefund = $this->validateSettingValue('refund_url');
            if ($rowRefund) {
                $this->_resourceConfig->deleteConfig(
                    'impact_impactintegration/existing_customer/refund_url',
                    'default',
                    0
                );
            } 
            // Insert refund url
            $this->_resourceConfig->saveConfig(
                'impact_impactintegration/existing_customer/refund_url',
                $urls['refund_url'],
                'default',
                0
            );

            /**
             *  Upadte Current UTT
             */
            // Firts delete de current UTT 
            $this->_resourceConfig->deleteConfig(
                'impact_impactintegration/existing_customer/utt_default',
                'default',
                0
            );
            // Validate if user input UTT
            $currentUTT = $saasler_script;
            if (isset($universal_tracking_tag) && !empty($universal_tracking_tag)) {
                $currentUTT = $universal_tracking_tag . $ircClickFunction;
            } 
            // Save New Current Universal Tracking Tag 
            $this->_resourceConfig->saveConfig(
                'impact_impactintegration/existing_customer/utt_default',
                $currentUTT,
                'default',
                0
            );
            // Remove the old UTT on Head and Style html head
            if ($rowConfig) {
                $headHTML= $rowConfig['value'];
                $headHTMLWithOutUTT = str_replace($oldUTT, "", $headHTML);
                $utt = $headHTMLWithOutUTT." ".$currentUTT;
            } else {
                $utt = $currentUTT;
            }

            // Insert core data
            $this->_resourceConfig->saveConfig(
                'design/head/includes',
                $utt,
                'stores',
                1
            );
        } else {
            // Remove in Head and Style in html head the UTT or Saasler script 
            if ($rowConfig) {
                $headHTML= $rowConfig['value'];
                $headHTMLWithOutUTT = str_replace($oldUTT, "", $headHTML);
                $utt = $headHTMLWithOutUTT;

                // Insert core data
                $this->_resourceConfig->saveConfig(
                    'design/head/includes',
                    $utt,
                    'stores',
                    1
                );
            } 
        }

        // Clean cache
        $this->flushCache();

        return $this;
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

    /**
     * Validate if exist conversion and refund url
     * 
     * @return row 
     */
    private function validateSettingValue($urlType)
    {
        // Validate if refund url exist
        $connection = $this->setup->getConnection();
        $select = $connection->select()
                                ->from('core_config_data')
                                ->where($connection->quoteIdentifier('path') . "= 'impact_impactintegration/existing_customer/".$urlType."'");
        $row = $connection->fetchRow($select);
        return $row;
    }

    /**
     * Get  core cofig value
     * 
     * @return row 
     */
    private function getCoreCofigValue($path)
    {
        // Validate if refund url exist
        $connection = $this->setup->getConnection();
        $select = $connection->select()
                                ->from('core_config_data')
                                ->where($connection->quoteIdentifier('path') . "= '".$path."'");
        $row = $connection->fetchRow($select);
        return $row;
    }
    /**
     * Flush cache
     *  
     *  @return void 
     */
    private function flushCache():void
    {
        $types = array('config','layout','block_html','collections','reflection','db_ddl','eav','config_integration','config_integration_api','full_page','translate','config_webservice');
        foreach ($types as $type) {
            $this->cacheTypeList->cleanType($type);
        }
        foreach ($this->cacheFrontendPool as $cacheFrontend) {
            $cacheFrontend->getBackend()->clean();
        }
    }
}
?>