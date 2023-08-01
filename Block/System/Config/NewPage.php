<?php
/**
 * Impact: Partnership Cloud for Magento
 *
 * @package     Impact_Integration
 * @copyright   Copyright (c) 2021 Impact. (https://impact.com)
 */

namespace Impact\Integration\Block\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Backend\Block\Template\Context;

/**
 * Class Button
 */
class NewPage extends Field
{
    /**
     * @var string $_template
     */
    protected $_template = 'Impact_Integration::system/config/newPage.phtml';

    /**
     * Button constructor.
     *
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        array $data = []
    ) {
        $this->data_from_db = $this->retrieveDataFromDb();
        parent::__construct($context, $data);
    }
 
    /**
     * Function render.
     *
     * @param Magento\Framework\Data\Form\Element\AbstractElement $element
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    /**
     * Function _getElementHtml.
     *
     * @param Magento\Framework\Data\Form\Element\AbstractElement $element
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->_toHtml();
    }

    /**
     * Function getAjaxUrl.
     */
    public function getAjaxUrl()
    {
        return $this->getUrl('Impact_Integration/system_config/button');
    }

    /**
     * Function getButtonHtml.
     */
    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock(
            \Magento\Backend\Block\Widget\Button::class
        )->setData(
            [
                'id' => 'btnid',
                'class' => 'primary',
                'label' => __('Uninstall')
            ]
        );

        return $button->toHtml();
    }

    /**
     * Function getButtonHtml.
     */
    private function retrieveDataFromDb()
    {
        $object = \Magento\Framework\App\ObjectManager::getInstance();
        $con = $object->get(\Magento\Framework\App\ResourceConnection::class)
        ->getConnection('\Magento\Framework\App\ResourceConnection::DEFAULT');

        $results = $con->fetchAll("
            SELECT * from core_config_data where path in ('impact_integration/existing_customer/account_sid',
            'impact_integration/existing_customer/auth_token',
            'impact_integration/existing_customer/program_id',
            'impact_integration/existing_customer/event_type_id',
            'impact_integration/existing_customer/universal_tracking_tag');
        ");

        $results = $this->encapsulateValues($results);
        return $results;
    }

    /**
     * Function encapsulateValues.
     *
     * @param array $results
     */
    private function encapsulateValues($results)
    {
        $var = [];
        $var_renew = [];
        if (!empty($results)) {
            foreach ($results as $data) {
                $var[] = $data['value'];
            }
            $var_renew['account_id'] = $var[0]??'';
            $var_renew['auth_token'] = $var[1]??'';
            $var_renew['program_id'] = $var[2]??'';
            $var_renew['event_type_id'] = $var[3]??'';
            $var_renew['universal_tracking_tag'] = $var[4]??'';
        }

        return $var_renew;
    }
}
