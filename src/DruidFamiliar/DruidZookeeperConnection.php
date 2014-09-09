<?php

//require '../../vendor/autoload.php';

namespace DruidFamiliar;

use DruidFamiliar\Exception;

class DruidZookeeperConnection implements IDruidConnection
{


    public function __construct($ip, $port) {
        return $this->connectToZooKeeper($ip, $port);
    }


    public function executeQuery(IDruidQuery $query)
    {
        $generatedQuery = $query->generateQuery();

        $client = new \Guzzle\Http\Client();

        // TODO Change this to actually hit/use zookeeper
        $request = $client->post('http://192.168.10.103/druid/v2/?', array("content-type" => "application/json"), json_encode($generatedQuery));

        // Send the request and parse the JSON response into an array
        try
        {
            $response = $request->send();

        }
        catch (\Guzzle\Http\Exception\CurlException $curlException)
        {
            // TODO try another server
            // if that fails, rethrow
            throw new $curlException;
        }

        $data = $response->json();

        $formattedResponse = $query->handleResponse($data);

        return $formattedResponse;
    }



    private function connectToZooKeeper($ip, $port)
    {
        return "yesItWorks";
    }

    public function isConnected()
    {
        return $this->connected;
    }

    public function disconnect()
    {
        if ( !$this->connected ) {
            return false;
        }

        // TODO Disconnect cleanly from server

        return true;
    }

}