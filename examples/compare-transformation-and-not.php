<?php

require_once('../vendor/autoload.php');
$examplesDir = dirname(__FILE__);
$examplesConfig = require_once($examplesDir . '/_examples-config.php');

$druidHost = $examplesConfig['druid-host'];
$druidPort = $examplesConfig['druid-port'];

date_default_timezone_set('America/Denver');

$c = new \DruidFamiliar\DruidNodeConnection($druidHost, $druidPort);

$q = new \DruidFamiliar\TimeBoundaryDruidQuery('referral-visit-test-data');

$r = $c->executeQuery($q);

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


$q = new \DruidFamiliar\TransformingTimeBoundaryDruidQuery('referral-visit-test-data');

$r = $c->executeQuery($q);

var_dump( $r );

//array(2) {
//    ["minTime"]=>
//  string(24) "2011-06-01T00:00:11.000Z"
//    ["maxTime"]=>
//  string(24) "2011-11-30T23:55:34.000Z"
//}