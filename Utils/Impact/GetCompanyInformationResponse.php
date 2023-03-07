<?php
/**
 * Impact: Partnership Cloud for Magento
 *
 * @package     Impact_Integration
 * @copyright   Copyright (c) 2021 Impact. (https://impact.com)
 */

namespace Impact\Integration\Utils\Impact;

/**
 * Class GetCompanyInformationResponse
 *
 * @package Impact\Integration\Utils\Impact
 */
class GetCompanyInformationResponse
{
    /**
     * @var code
     */
    private $code;

    /**
     * @var data
     */
    private $data;

    /**
     * GetCompanyInformationResponse constructor
     *
     * @param mixed $accountSid
     */
    public function __construct($response)
    {
        $this->code = $response->getStatusCode();
        if (!$this->failed()) {
            $this->parseXMLResponse($response->getBody());
        }
    }

    /**
     *  Failed function
     * @return bool
     */
    public function failed()
    {
        return $this->code >= 400;
    }

    /**
     *  getData function
     * @return json_encode
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     *  parseXMLResponse function
     * @return void
     */
    private function parseXMLResponse($body)
    {
        $xmlObject = simplexml_load_string($body);
        $root = $xmlObject->getName();
        $jsonString = json_encode($xmlObject);
        $jsonString = json_encode([$root => json_decode($jsonString)]);
        $this->data = json_decode($jsonString, true);
    }
}
