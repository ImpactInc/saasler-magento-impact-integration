<?php
/**
 * Impact: Partnership Cloud for Magento
 *
 * @package     Impact_Integration
 * @copyright   Copyright (c) 2021 Impact. (https://impact.com)
 */

namespace Impact\Integration\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class ImpactApiService
 *
 * @package Impact\Integration\Service
 */
class ImpactApiService
{
    /**
     *
     * Access Token
     *
     * @var accessToken
     */
    private $accessToken;

    /**
     *
     * Client GuzzleHttp
     *
     * @var client
     */
    private $client;

    /**
     *
     * API request endpoint
     *
     * @var endpoint
     */
    private $endpoint;

    /**
     *
     * API request method
     *
     * @var method
     */
    private $method;

    /**
     *
     * API request body
     *
     * @var body
     */
    private $body;

    /**
     * ImpactApiService constructor
     *
     * @param string $accessToken
     * @param string $endpoint
     * @param string $method
     * @param array $body
     */
    public function __construct(
        $accessToken,
        $endpoint,
        $method,
        $body
    ) {
        $this->accessToken = $accessToken;
        $this->endpoint = $endpoint;
        $this->method = $method;
        $this->body = $body;
    }

     /**
      * Fetch some data from API
      *
      * @return Response
      */
    public function execute()
    {
        $response = [];
        // New GuzzleHttp Client
        $this->client = new Client([
            'base_uri' => $this->endpoint,
            'timeout'  => 5.0,
        ]);
        
        try {
            // Authorization
            $headers = ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' .$this->accessToken];
            // Send Data
            $apiRequest = $this->endpoint;
            $request = new Request($this->method, $apiRequest, $headers, $this->body);
            $response = $this->client->send($request, ['timeout' => 5]);
        } catch (GuzzleException $exception) {
            \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->info($exception->getMessage());
        }
        return $response;
    }
}
