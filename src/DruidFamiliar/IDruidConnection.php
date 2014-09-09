<?php

namespace DruidFamiliar;

interface IDruidConnection
{

    public function __construct($ip, $port);

    public function executeQuery(IDruidQuery $query);

}