<?php

//require '../../vendor/autoload.php';

namespace DruidFamiliar;

use DruidFamiliar\Exception;

class DruidNodeConnection implements IDruidConnection
{
    private $ip;
    private $port;
    private $endpoint;
    private $protocol;

    public function __construct($ip, $port, $endpoint = '/druid/v2/', $protocol = 'http') {
        $this->ip = $ip;
        $this->port = $port;
        $this->endpoint = $endpoint;
        $this->protocol = $protocol;
    }

    public function getBaseUrl()
    {
        $baseUrl = $this->protocol . '://' . $this->ip . ':' . $this->port;
        $url = $baseUrl . $this->endpoint;
        return $url;
    }

    public function createRequest($query)
    {
        $client = new \Guzzle\Http\Client();

        $request = $client->post(
            $this->getBaseUrl(),
            array("content-type" => "application/json"),
            json_encode($query)
        );

        return $request;
    }


    public function executeQuery(IDruidQuery $query)
    {
        $generatedQuery = $query->generateQuery();

        // Create a POST request
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

        $data = $response->json();

        $formattedResponse = $query->handleResponse($data);

        return $formattedResponse;
    }

}