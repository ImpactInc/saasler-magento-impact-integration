<?php
/**
* Impact: Partnership Cloud for Magento
*
* @package     Impact_Itegration
* @copyright   Copyright (c) 2021 Impact. (https://impact.com)
*/

namespace impact_tech\module-magento-integration\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use impact_tech\module-magento-integration\Service\ImpactApiService; 
use Magento\Framework\Setup\ModuleDataSetupInterface;
use impact_tech\module-magento-integration\Helper\Data;

/**
 * Class SalesOrderCreditmemoRefund
 *
 * @package impact_tech\module-magento-integration\Observer
 */
class SalesOrderCreditmemoRefund implements ObserverInterface
{   
    /**
     * @var setup
     */
    private $setup;

    /**
     * @var helper
     */
    private $helper;
    
    /**
     * SalesOrderCreditmemoRefund constructor.
     * 
     * @param ModuleDataSetupInterface $setup
     * @param Data $helper 
     */
    public function __construct(
        ModuleDataSetupInterface $setup, 
        Data $helper
    )
    {
        $this->setup = $setup;
        $this->helper = $helper;
    }

    /**
    * Execute Function
    * 
    * @param EventObserver $observer
    * @return $this
    */
    public function execute(EventObserver $observer)
    {
        // Validate if module is enable
        if ($this->helper->isEnabled() && !empty($this->helper->getRefundUrl()) && !is_null($this->helper->getRefundUrl()) ) {
            /**
             * @var \Magento\Sales\Model\Order\Creditmemo $creditMemo
             */
            $creditMemo = $observer->getData('creditmemo');
            $order = $creditMemo->getOrder();
            $entityId = $order->getEntityId();

            $saaslerApiService = new ImpactApiService('', $this->helper->getRefundUrl(), 'POST', json_encode(['order_id' => $entityId]));
            $response = $saaslerApiService->execute();
            $responseBody = $response->getBody();
        }
        
        return $this; 
    }    
}