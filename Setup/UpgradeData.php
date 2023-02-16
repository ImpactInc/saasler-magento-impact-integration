<?php
/**
* Impact: Partnership Cloud for Magento
*
* @package     Impact_Integration
* @copyright   Copyright (c) 2021 Impact. (https://impact.com)
*/


namespace Impact\Integration\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Integration\Model\ConfigBasedIntegrationManager;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var ConfigBasedIntegrationManager
     */

    private $integrationManager;

    /**
     * @param ConfigBasedIntegrationManager $integrationManager
     */

    public function __construct(ConfigBasedIntegrationManager $integrationManager)
    {
        $this->integrationManager = $integrationManager;
    }

    /**
     * {@inheritdoc}
     */

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->integrationManager->processIntegrationConfig(['ImpactIntegration']);
    }
}
