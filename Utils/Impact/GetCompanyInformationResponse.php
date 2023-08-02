<?php
/**
 * Impact: Partnership Cloud for Magento
 *
 * @package     Impact_Integration
 * @copyright   Copyright (c) 2021 Impact. (https://impact.com)
 */

namespace Impact\Integration\Utils\Impact;

/**
 * Class GetCompanyInformationResponse - Response for get company information
 *
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
     * @param mixed $response
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
     *
     * @return bool
     */
    public function failed()
    {
        return $this->code >= 400;
    }

    /**
     *  GetData function
     *
     * @return json_encode
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     *  ParseXMLResponse function
     *
     * @param String $body
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
