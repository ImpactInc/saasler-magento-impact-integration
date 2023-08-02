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
use Psr\Log\LoggerInterface;

/**
 * Class ImpactHttpClient - Http client for Impact API
 *
 */
class ImpactHttpClient
{

    /**
     *
     * @var accountSid
     */
    private $accountSid;
    /**
     *
     * @var authToken
     */
    private $authToken;

    /**
     * ImpactHttpClient constructor.
     *
     * @param String $accountSid
     * @param String $authToken
     */
    public function __construct($accountSid, $authToken)
    {
        $this->accountSid = $accountSid;
        $this->authToken = $authToken;
    }

    /**
     * Get company information
     *
     * @param String $accountSid
     * @param String $authToken
     * @return mixed
     * GetCompanyInformationResponse or false
     */
    public function getCompanyInformation()
    {
        $accountSid = $this->accountSid;
        $authToken = $this->authToken;
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
            $logger = \Magento\Framework\App\ObjectManager::getInstance()->get(LoggerInterface::class);
            $logger->info($exception->getMessage());
            return false;
        }

        return new GetCompanyInformationResponse($response);
    }
}
