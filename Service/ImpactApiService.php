<?php

namespace impact\impactintegration\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class ImpactApiService
 */
class ImpactApiService
{
    /**
     * 
     * Access Token 
     */
    private $accessToken; 

    /**
     * 
     * Client GuzzleHttp
     */
    private $client; 

    /**
     * 
     * API request endpoint
     */
    private $endpoint; 

    /**
     * 
     * API request method
     */
    private $method; 

    /**
     * 
     * API request body
     */
    private $body; 

    /**
     * ImpactApiService constructor
     *
     * @param string $endpoint
     */
    public function __construct(
        $accessToken,
        $endpoint, 
        $method, 
        $body
    )
    {
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
            throw new NoSuchEntityException($exception->getMessage());
        }
        return $response; 
    }

    
}