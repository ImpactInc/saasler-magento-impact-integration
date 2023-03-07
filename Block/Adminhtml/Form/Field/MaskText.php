<?php
/**
 * Impact: Partnership Cloud for Magento
 *
 * @package     Impact_Integration
 * @copyright   Copyright (c) 2021 Impact. (https://impact.com)
 */

namespace Impact\Integration\Block\Adminhtml\Form\Field;

use Magento\Framework\Escaper;

class MaskText extends \Magento\Framework\Data\Form\Element\Text
{
    /**
     * @param Factory $factoryElement
     * @param CollectionFactory $factoryCollection
     * @param Escaper $escaper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Data\Form\Element\Factory $factoryElement,
        \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection,
        \Magento\Framework\Escaper $escaper,
        array $data = []
    ) {
        $data = $this->maskText($data);
        parent::__construct($factoryElement, $factoryCollection, $escaper, $data);
        $this->setType('text');
        $this->setExtType('textfield');
        $this->setValue('');
    }

    private function maskText($data)
    {
        if (isset($data["value"])) {
            $data["placeholder"] = str_repeat('*', strlen($data["value"])-4) . substr($data["value"], -4);
        }
        return $data;
    }
}
