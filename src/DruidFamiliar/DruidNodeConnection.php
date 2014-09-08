<?php

//require '../../vendor/autoload.php';

namespace DruidFamiliar;

use DruidFamiliar\Exception;

class DruidNodeConnection implements IDruidConnection
{

    public function executeQuery(IDruidQuery $query)
    {
        $generatedQuery = $query->generateQuery();

        // TODO Connect and Execute query

        $response = Array();

        $formattedResponse = $query->handleResponse($response);

        return $formattedResponse;
    }



    private $connected = false;

    public function connect($ip, $port) {
        return $this->connectToNode($ip, $port);
    }

    private function connectToNode($ip, $port)
    {
        if (false) {
            throw new DruidBusyException();
        }
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