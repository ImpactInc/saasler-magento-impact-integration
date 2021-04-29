<?php
namespace Impact\Integration\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Impact\Integration\Service\ImpactApiService; 
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Impact\Integration\Helper\Data;

class SalesOrderCreditmemoRefund implements ObserverInterface
{   
    private $setup;
    private $helper;
    
    public function __construct(
        ModuleDataSetupInterface $setup, 
        Data $helper
    )
    {
        $this->setup = $setup;
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
            $urls = [];
            // Validate if refund url exist
            $connection = $this->setup->getConnection();
            $select = $connection->select()
                                    ->from('core_config_data')
                                    ->where($connection->quoteIdentifier('path') . "= 'impact_integration/existing_customer/refund_url'");
            $row = $connection->fetchRow($select);
            if ($row) {
                $refund_url = $row['value'];
                // Send data POST to Saasler
                if (isset($refund_url)) {
                    /**
                     * @var \Magento\Sales\Model\Order\Creditmemo $creditMemo
                     */
                    $creditMemo = $observer->getData('creditmemo');
                    $order = $creditMemo->getOrder();
                    $incrementId = $order->getIncrementId();

                    $saaslerApiService = new ImpactApiService('', $refund_url, 'POST', json_encode(['order_id' => $incrementId]));
                    $response = $saaslerApiService->execute();
                    $responseBody = $response->getBody();
                    $responseContent = $responseBody->getContents();
                }
            } 
        }
        
        return $this; 
    }    
}