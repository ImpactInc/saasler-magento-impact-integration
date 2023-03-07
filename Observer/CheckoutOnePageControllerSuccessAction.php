<?php
/**
 * Impact: Partnership Cloud for Magento
 *
 * @package     Impact_Integration
 * @copyright   Copyright (c) 2021 Impact. (https://impact.com)
 */

namespace Impact\Integration\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Impact\Integration\Service\ImpactApiService;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Impact\Integration\Helper\Data;

/**
 * Class CheckoutOnePageControllerSuccessAction
 *
 * @package Impact\Integration\Observer
 */
class CheckoutOnePageControllerSuccessAction implements ObserverInterface
{
    /**
     * @var CookieManagerInterface
     */
    protected $cookieManager;

    /**
     * @var helper
     */
    private $helper;

    /**
     * CheckoutOnePageControllerSuccessAction constructor.
     *
     * @param CookieManagerInterface $cookieManager
     * @param Data $helper
     */
    public function __construct(
        CookieManagerInterface $cookieManager,
        Data $helper
    ) {
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
        if ($this->helper->isEnabled() && !empty($this->helper->getConversionUrl()) && !is_null($this->helper->getConversionUrl())) {
            // Get data from order
            $order = $observer->getEvent()->getOrder();
            $entityId = $order->getEntityId();
            $irclickid = $this->cookieManager->getCookie('irclickid');

            $saaslerApiService = new ImpactApiService('', $this->helper->getConversionUrl(), 'POST', json_encode(['order_id' => $entityId, 'irclickid' => $irclickid]));
            $response = $saaslerApiService->execute();
            $responseBody = $response->getBody();
            $responseContent = $responseBody->getContents();
        }
        return $this;
    }
}
