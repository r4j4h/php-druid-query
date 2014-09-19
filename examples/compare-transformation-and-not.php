<?php

require_once('../vendor/autoload.php');
$examplesDir = dirname(__FILE__);
$examplesConfig = require_once($examplesDir . '/_examples-config.php');

$druidHost = $examplesConfig['druid-host'];
$druidPort = $examplesConfig['druid-port'];
$druidDataSource = $examplesConfig['druid-dataSource'];

date_default_timezone_set('America/Denver');

$c = new \DruidFamiliar\QueryExecutor\DruidNodeDruidQueryExecutor($druidHost, $druidPort);

$q = new \DruidFamiliar\QueryGenerator\TimeBoundaryDruidQueryGenerator();
$p = new \DruidFamiliar\QueryParameters\TimeBoundaryQueryParameters($druidDataSource);
$r = $c->executeQuery($q, $p, new DruidFamiliar\ResponseHandler\DoNothingResponseHandler());

var_dump( $r );

//array(1) {
//    [0]=>
//  array(2) {
//        ["timestamp"]=>
//    string(24) "2011-06-01T00:00:11.000Z"
//        ["result"]=>
//    array(2) {
//            ["minTime"]=>
//      string(24) "2011-06-01T00:00:11.000Z"
//            ["maxTime"]=>
//      string(24) "2011-11-30T23:55:34.000Z"
//    }
//  }
//}


$q = new \DruidFamiliar\QueryGenerator\TimeBoundaryDruidQueryGenerator($druidDataSource);
$p = new \DruidFamiliar\QueryParameters\TimeBoundaryQueryParameters($druidDataSource);
$r = $c->executeQuery($q, $p, new DruidFamiliar\ResponseHandler\TimeBoundaryResponseHandler());


var_dump( $r );

//object(DruidFamiliar\Response\TimeBoundaryResponse)#11 (2) {
//  ["minTime"]=>
//  string(24) "2008-02-06T11:47:39.000Z"
//  ["maxTime"]=>
//  string(24) "2008-12-31T19:36:48.000Z"
//}
