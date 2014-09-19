<?php
/**
 * DruidFamiliar Stdout TimeBoundary Printer Example
 *
 * Run this via the command line, e.g. `php stdout-printer.php`
 * and you should see nicely formatted time boundary data. :)
 */

use DruidFamiliar\Response\TimeBoundaryResponse;

require_once('../vendor/autoload.php');
$examplesDir = dirname(__FILE__);
$examplesConfig = require_once($examplesDir . '/_examples-config.php');

$druidHost = $examplesConfig['druid-host'];
$druidPort = $examplesConfig['druid-port'];
$druidDataSource = $examplesConfig['druid-dataSource'];

date_default_timezone_set('America/Denver');

$c = new \DruidFamiliar\DruidNodeConnection($druidHost, $druidPort);

$q = new \DruidFamiliar\QueryGenerator\TimeBoundaryDruidQueryGenerator($druidDataSource);
$p = new \DruidFamiliar\QueryParameters\TimeBoundaryQueryParameters($druidDataSource);
/**
 * @var TimeBoundaryResponse $r
 */
$r = $c->executeQuery($q, $p, new DruidFamiliar\ResponseHandler\TimeBoundaryResponseHandler());

echo "TimeBoundary data for DataSource \"$druidDataSource\": ";

$startTime = new DateTime( $r->minTime );
$endTime = new DateTime( $r->maxTime );

$formattedStartTime = $startTime->format("F m, Y h:i:s A");
$formattedEndTime = $endTime->format("F m, Y h:i:s A");


echo $formattedStartTime . "  to " . $formattedEndTime . "\n";

// Outputs:
// TimeBoundary data for DataSource "referral-visit-test-data": June 06, 2011 12:00:11 AM  to June 06, 2011 12:00:11 AM

