<?php

require_once('vendor/autoload.php');

date_default_timezone_set('America/Denver');

$c = new \DruidFamiliar\DruidNodeConnection("192.168.10.103", '9003');

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