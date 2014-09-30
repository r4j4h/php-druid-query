<?php

namespace DruidFamiliar\QueryExecutor;

use DruidFamiliar\Exception;
use DruidFamiliar\Interfaces\IDruidQueryExecutor;
use DruidFamiliar\Interfaces\IDruidQueryGenerator;
use DruidFamiliar\Interfaces\IDruidQueryParameters;
use DruidFamiliar\Interfaces\IDruidQueryResponseHandler;
use Guzzle\Http\Message\Response;
use Guzzle\Http\Client;
use Guzzle\Http\Exception\CurlException;

/**
 * Class JSONDruidNodeDruidQueryExecutor
 * @package   DruidFamiliar\QueryExecutor
 * @author    Jasmine Hegman
 * @version   1.0
 * @category  WebPT
 * @copyright Copyright (c) 2014 WebPT, Inc.
 */
class JSONDruidNodeDruidQueryExecutor implements IDruidQueryExecutor
{
    /**
     * An IP address
     * @access private
     * @var string
     */
    private $ip;
    /**
     * A host port
     * @access private
     * @var int
     */
    private $port;
    /**
     * The endpoint (host)
     * @access private
     * @var string
     */
    private $endpoint;
    /**
     * The protocol to be used
     * @access private
     * @var string
     */
    private $protocol;

    /**
     * Class constructor
     * @param string $ip
     * @param int    $port
     * @param string $endpoint
     * @param string $protocol
     */
    public function __construct($ip, $port, $endpoint = '/druid/v2/', $protocol = 'http')
    {
        $this->ip       = $ip;
        $this->port     = $port;
        $this->endpoint = $endpoint;
        $this->protocol = $protocol;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        $baseUrl = $this->protocol . '://' . $this->ip . ':' . $this->port;
        $url     = $baseUrl . $this->endpoint;
        return $url;
    }

    /**
     * @param $query
     *
     * @return \Guzzle\Http\Message\RequestInterface
     */
    public function createRequest($query)
    {
        $client = new Client();

        $request = $client->post($this->getBaseUrl(), array("content-type" => "application/json"), json_encode($query));

        return $request;
    }


    public function executeQuery(IDruidQueryGenerator $queryGenerator, IDruidQueryParameters $params, IDruidQueryResponseHandler $responseHandler)
    {
        $params->validate();

        $generatedQuery = $queryGenerator->generateQuery($params);

        // Create a POST request
        $request = $this->createRequest($generatedQuery);

        // Send the request and parse the JSON response into an array
        try
        {
            $response = $request->send();
        }
        catch(CurlException $curlException)
        {
            throw new $curlException;
        }

        $data = $this->parseResponse($response);

        $formattedResponse = $responseHandler->handleResponse($data);

        return $formattedResponse;
    }

    /**
     * @param Response $rawResponse
     *
     * @return mixed
     */
    protected function parseResponse($rawResponse)
    {
        $formattedResponse = $rawResponse->json();

        return $formattedResponse;
    }
}