<?php
/**
 * Impact: Partnership Cloud for Magento
 *
 * @package     Impact_Integration
 * @copyright   Copyright (c) 2021 Impact. (https://impact.com)
 */
declare(strict_types=1);

namespace Impact\Integration\Setup\Patch\Data;

use Magento\Integration\Model\ConfigBasedIntegrationManager;

/**
 * This patch will install de Impact Integration
 */
class ImpactIntegration implements \Magento\Framework\Setup\Patch\DataPatchInterface
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
     * @inheritdoc
     */
    public function apply()
    {
        $this->integrationManager->processIntegrationConfig(['ImpactIntegration']);
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [];
    }
}
