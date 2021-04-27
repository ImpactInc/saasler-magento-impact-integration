<?php
namespace impact\impactintegration\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use impact\impactintegration\Service\ImpactApiService; 
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Stdlib\CookieManagerInterface;
use impact\impactintegration\Helper\Data;

class SalesOrderInvoicePay implements ObserverInterface
{   
    private $setup;
    /**
     * @var CookieManagerInterface
     */
    protected $cookieManager;
    private $helper; 

    public function __construct(
        ModuleDataSetupInterface $setup, 
        CookieManagerInterface $cookieManager,  
        Data $helper
    )
    {
        $this->setup = $setup;
        $this->cookieManager = $cookieManager;
        $this->helper = $helper;
    }

    /**
    * @param EventObserver $observer
    * @return $this
    */
    public function execute(EventObserver $observer)
    {
        // Validate if module is enable
        if ($this->helper->isEnabled()) {
            $irclickid = "";
            // Validate if conversion url exist
            $connection = $this->setup->getConnection();
            $select = $connection->select()
                                    ->from('core_config_data')
                                    ->where($connection->quoteIdentifier('path') . "= 'impact_impactintegration/existing_customer/conversion_url'");
            $row = $connection->fetchRow($select);
            if ($row) {
                $conversion_url = $row['value'];
                // Send data POST to Saasler
                if (isset($conversion_url)) {
                    // Get data from order
                    $invoice = $observer->getEvent()->getInvoice();
                    $order = $invoice->getOrder();
                    $incrementId = $order->getIncrementId();
                    $irclickid = $this->cookieManager->getCookie('irclickid');

                    $saaslerApiService = new ImpactApiService('', $conversion_url, 'POST', json_encode(['order_id' => $incrementId, 'irclickid' => $irclickid]));
                    $response = $saaslerApiService->execute();
                    $responseBody = $response->getBody();
                    $responseContent = $responseBody->getContents();
                }
            }
        }
        return $this;
    }    
}