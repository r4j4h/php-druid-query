<?php

namespace DruidFamiliar;

interface IDruidConnection
{

    public function connect($ip, $port);

    public function executeQuery(IDruidQuery $query);

}