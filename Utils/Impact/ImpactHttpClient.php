<?php
/**
 * Impact: Partnership Cloud for Magento
 *
 * @package     Impact_Integration
 * @copyright   Copyright (c) 2021 Impact. (https://impact.com)
 */

namespace Impact\Integration\Utils\Impact;

use Impact\Integration\Utils\Impact\GetCompanyInformationResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ClientException;

/**
 * Class ImpactHttpClient
 *
 * @package Impact\Integration\Utils\Impact
 */
class ImpactHttpClient
{
    /**
     * Get company information
     *
     * @param String $accountSid
     * @param String $authToken
     * @return mixed
     * GetCompanyInformationResponse or false
     */
    public static function getCompanyInformation($accountSid, $authToken)
    {
        $response = [];
        $endpoint = "https://api.impact.com/";
        // New GuzzleHttp Client
        $client = new Client([
            'base_uri' => $endpoint,
            'timeout'  => 5.0,
        ]);

        try {
            // Authorization
            $headers = ['Authorization' => "Basic " . base64_encode($accountSid . ":" . $authToken)];
            // Request api
            $response = $client->request('GET', "/Advertisers/$accountSid/CompanyInformation", ['headers' => $headers]);

        } catch (ClientException $exception) {
            //exception is thrown for 400 level errors
            $response = $exception->getResponse();

        } catch (ConnectException $exception) {
            //exception is thrown in the event of a networking error.
            \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->info($exception->getMessage());
            return false;
        }

        return new GetCompanyInformationResponse($response);
    }
}
