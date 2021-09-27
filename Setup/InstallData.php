<?php
/**
* Impact: Partnership Cloud for Magento
*
* @package     Impact_Itegration
* @copyright   Copyright (c) 2021 Impact. (https://impact.com)
*/

namespace impact_tech\module-magento-integration\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Integration\Model\ConfigBasedIntegrationManager;
use Magento\Framework\Setup\InstallDataInterface;

/**
 * Class InstallData
 *
 * @package impact_tech\module-magento-integration\Setup
 */
class InstallData implements InstallDataInterface
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

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->integrationManager->processIntegrationConfig(['ImpactIntegration']);
    }
}
