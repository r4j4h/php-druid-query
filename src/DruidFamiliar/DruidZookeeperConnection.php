<?php

//require '../../vendor/autoload.php';

namespace DruidFamiliar;

use DruidFamiliar\Exception;

class DruidZookeeperConnection implements IDruidConnection
{
    private $connected = false;

    public function connect($ip, $port) {
        return $this->connectToZooKeeper($ip, $port);
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

    public function executeQuery(IDruidQuery $query)
    {
        return $query->execute();
    }
}