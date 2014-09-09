<?php

//require '../../vendor/autoload.php';

namespace DruidFamiliar;

use DruidFamiliar\Exception;

class DruidNodeConnection implements IDruidConnection
{
    private $ip;
    private $port;
    private $endpoint;

    public function __construct($ip, $port, $endpoint = '/druid/v2/') {
        $this->ip = $ip;
        $this->port = $port;
        $this->endpoint = $endpoint;
    }

    public function connect($ip, $port, $endpoint = '/druid/v2/') {
        $this->ip = $ip;
        $this->port = $port;
        $this->endpoint = $endpoint;
    }

    public function executeQuery(IDruidQuery $query)
    {
        $generatedQuery = $query->generateQuery();

        $client = new \Guzzle\Http\Client();

        $baseUrl = 'http://' . $this->ip . ':' . $this->port;
        $url = $baseUrl . $this->endpoint;

        // Create a tweet using POST
        $request = $client->post($url, array("content-type" => "application/json"), json_encode($generatedQuery));

        // Send the request and parse the JSON response into an array
        try
        {
            $response = $request->send();

        }
        catch (\Guzzle\Http\Exception\CurlException $curlException)
        {
            // TODO could try again once in a second if druid was busy?

            throw new $curlException;
        }

        $data = $response->json();

        $formattedResponse = $query->handleResponse($data);

        return $formattedResponse;
    }

}