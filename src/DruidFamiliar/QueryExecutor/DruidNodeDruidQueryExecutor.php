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

class DruidNodeDruidQueryExecutor implements IDruidQueryExecutor
{
    /**
     * IP to Druid node
     * @var String
     */
    protected $ip;

    /**
     * Port to Druid node
     * @var Number
     */
    protected $port;

    /**
     * The endpoint to hit on the Druid node
     * @var string
     */
    protected $endpoint;

    /**
     * The protocol the Druid node is listening on. (http or https)
     *
     * @var int
     */
    protected $protocol;

    /**
     * HTTP Method to use
     *
     * @var string
     */
    protected $httpMethod = 'POST';

    public function __construct($ip, $port, $endpoint = '/druid/v2/', $protocol = 'http', $httpMethod = 'POST') {
        $this->ip = $ip;
        $this->port = $port;
        $this->endpoint = $endpoint;
        $this->setProtocol($protocol);
        $this->setHttpMethod($httpMethod);
    }

    public function getBaseUrl()
    {
        $baseUrl = $this->protocol . '://' . $this->ip . ':' . $this->port;
        $url = $baseUrl . $this->endpoint;
        return $url;
    }

    /**
     * Create a Guzzle Request object using the given JSON parameters
     *
     * @param string $query JSON String
     * @return \Guzzle\Http\Message\RequestInterface
     * @throws \Exception
     */
    public function createRequest($query)
    {
        $client = new \Guzzle\Http\Client();

        $method = $this->httpMethod;
        $uri = $this->getBaseUrl();
        $headers = array("content-type" => "application/json");
        $options = array();

        if ( $method === 'POST' )
        {
            $postBody = $query;
            $request = $client->createRequest( $method, $uri, $headers, $postBody, $options );
        }
        else if ( $method === 'GET' )
        {
            $request = $client->createRequest( $method, $uri, $headers, null, $options );
            if ( $query ) {
                $queryObject = json_decode($query, true);
                $query = $request->getQuery();
                foreach ($queryObject as $key => $val) {
                    $query->set($key, $val);
                }
            }
        }
        else
        {
            throw new Exception('Unexpected HTTP Method: ' . $method);
        }

        return $request;
    }

    /**
     * Execute a Druid query using the provided query generator, parameters, and response payload handler.
     *
     * See DruidFamiliar\ResponseHandler\DoNothingResponseHandler.
     *
     * @param IDruidQueryGenerator $queryGenerator
     * @param IDruidQueryParameters $params
     * @param IDruidQueryResponseHandler $responseHandler
     * @return mixed
     */
    public function executeQuery(IDruidQueryGenerator $queryGenerator, IDruidQueryParameters $params, IDruidQueryResponseHandler $responseHandler)
    {
        $params->validate();

        $generatedQuery = $queryGenerator->generateQuery($params);

        // Create a request
        $request = $this->createRequest( $generatedQuery );

        // Send the request and parse the JSON response into an array
        try
        {
            $response = $request->send();
        }
        catch (\Guzzle\Http\Exception\CurlException $curlException)
        {
            throw new $curlException;
        }

        $formattedResponse = $responseHandler->handleResponse($response);

        return $formattedResponse;
    }

    /**
     * Get the HTTP Method.
     *
     * @return string
     */
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    /**
     * Set the HTTP Method.
     *
     * Supported methods are: GET, POST
     *
     * @param $method
     * @throws \Exception
     */
    public function setHttpMethod($method)
    {
        $allowed_methods = array('GET', 'POST');

        $method = strtoupper( $method );

        if ( !in_array( $method, $allowed_methods ) ) {
            throw new Exception('Unsupported HTTP Method: ' . $method . '. Supported methods are: ' . join($allowed_methods, ', '));
        }

        $this->httpMethod = $method;
    }

    /**
     * Get the protocol.
     *
     * @return string
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * Set the protocol.
     *
     * Supported protocols are: http, https
     *
     * @param string $protocol
     */
    public function setProtocol($protocol)
    {
        $allowedProtocols = array('http', 'https');

        $protocol = strtolower( $protocol );

        if ( !in_array( $protocol,$allowedProtocols ) ) {
            throw new Exception('Unsupported Protocol: ' . $protocol . '. Supported protocols are: ' . join($allowedProtocols, ', '));
        }

        $this->protocol = $protocol;
    }

    /**
     * @return String
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param String $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return Number
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param Number $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @param string $endpoint
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
    }
}
