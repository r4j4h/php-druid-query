<?php

require_once('vendor/autoload.php');

date_default_timezone_set('America/Denver');

$c = new \DruidFamiliar\DruidNodeConnection("192.168.10.103", '9003');

$q = new \DruidFamiliar\TimeBoundaryDruidQuery('referral-visit-test-data');

$r = $c->executeQuery($q);

var_dump( $r );

$q = new \DruidFamiliar\SegmentMetadataDruidQuery('referral-visit-test-data');

$r = $c->executeQuery($q);

var_dump( $r );

