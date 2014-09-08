<?php

namespace DruidFamiliar;

use DruidFamiliar\Exception;

class FakeDruidTimeBoundaryConnection implements IDruidConnection
{

    public function executeQuery(IDruidQuery $query)
    {
        $generatedQuery = $query->generateQuery();

        // "Connect and Execute query"
        if ( $generatedQuery['queryType'] !== 'timeBoundary' ) {
            throw new \Exception('Malformed Query');
        }

        $fakeResponse = Array(
            0 => Array(
                "timestamp" => "2013-05-09T18:24:00.000Z",
                "result" => Array(
                    "minTime" => "2013-05-09T18:24:00.000Z",
                    "maxTime" => "2013-05-09T18:37:00.000Z",
                ),
            ),
        );

        $response = $fakeResponse;

        $formattedResponse = $query->handleResponse($response);

        return $formattedResponse;
    }



    private $connected = false;

    public function connect($ip, $port) {
        $this->connected = true;
        return true;
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

        // Disconnect cleanly from server
        $this->connected = false;

        return true;
    }

}