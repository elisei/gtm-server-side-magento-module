<?php

namespace Stape\Gtm\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Stape\Gtm\Model\ConfigProvider;
use Stape\Gtm\Model\Webhook\Adapter;

class OrderCreditmemoRefundObserver implements ObserverInterface
{
    /**
     * @var ConfigProvider $configProvider
     */
    private $configProvider;

    /**
     * @var Adapter $adapter
     */
    private $adapter;

    /**
     * Define class dependencies
     *
     * @param ConfigProvider $configProvider
     * @param Adapter $adapter
     */
    public function __construct(
        ConfigProvider $configProvider,
        Adapter $adapter
    ) {
        $this->configProvider = $configProvider;
        $this->adapter = $adapter;
    }

    /**
     * Execute refund webhook call
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {

        /** @var \Magento\Sales\Model\Order\Creditmemo $creditmemo */
        $creditmemo = $observer->getCreditmemo();
        $scope = $creditmemo->getOrder()->getStoreId();
        if ($this->configProvider->webhooksEnabled($scope)
            && $this->configProvider->isRefundWebhookEnabled($scope)
        ) {
            $this->adapter->refund($creditmemo);
        }
    }
}