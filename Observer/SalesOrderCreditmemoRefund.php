<?php
/**
* Impact: Partnership Cloud for Magento
*
* @package     Impact_Itegration
* @copyright   Copyright (c) 2021 Impact. (https://impact.com)
*/

namespace Impact\Integration\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Impact\Integration\Service\ImpactApiService; 
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Impact\Integration\Helper\Data;

/**
 * Class SalesOrderCreditmemoRefund
 *
 * @package Impact\Integration\Observer
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
            $incrementId = $order->getIncrementId();

            $saaslerApiService = new ImpactApiService('', $this->helper->getRefundUrl(), 'POST', json_encode(['order_id' => $incrementId]));
            $response = $saaslerApiService->execute();
            $responseBody = $response->getBody();
        }
        
        return $this; 
    }    
}