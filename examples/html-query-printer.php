<?php
/**
 * DruidFamiliar HTML TimeBoundary Printer Example
 *
 * Point your browser at this through a web server and you should see nicely formatted time boundary data. :)
 */

use DruidFamiliar\ExampleGroupByQueries\ReferralsByCompanyGroupByQueryParameters;
use DruidFamiliar\ExampleGroupByQueries\ReferralsByCompanyGroupByTaskParameters;
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

$q2 = new \DruidFamiliar\ExampleGroupByQueries\ReferralsByCompanyGroupByQueryGenerator();
$p2 = new ReferralsByCompanyGroupByQueryParameters( $druidDataSource, '2006-01-01T00:00', '2015-01-01T00' );
$r2 = $c->executeQuery($q2, $p2, new \DruidFamiliar\ResponseHandler\DoNothingResponseHandler());


$startTime = new DateTime( $r->minTime );
$endTime = new DateTime( $r->maxTime );

$formattedStartTime = $startTime->format("F m, Y h:i:s A");
$formattedEndTime = $endTime->format("F m, Y h:i:s A");

$groupByHeadRows = <<<TABLEHEADROW
<tr>
    <th>timestamp</th>
    <th>companyId</th>
    <th>facilityId</th>
    <th>referrals</th>
</tr>
TABLEHEADROW;
;
$groupByBodyRows = '';

foreach ( $r2 as $index => $chunk)
{
    $timestamp = $chunk['timestamp'];
    $companyId = $chunk['event']['company_id'];
    $facilityId = $chunk['event']['facility_id'];
    $referrals = $chunk['event']['referral_count'];

    $groupByBodyRows .= <<<TABLEROW
<tr>
    <td>$timestamp</td>
    <td>$companyId</td>
    <td>$facilityId</td>
    <td>$referrals</td>
</tr>
TABLEROW;

}


echo <<<HTML_BODY
<!doctype html>
<html>
    <head>
        <style>
            .basic-table--table {
                border-collapse: collapse;
                width: 100%;

            }

            .basic-table--table,
             .basic-table--table th,
             .basic-table--table td {
                border: 1px solid #C36182;
            }


             .basic-table--table th {
                background-color: #C36182;
                color: white;
                padding: .5em 1em;
             }

             .basic-table--table td {
                padding: .25em 1em;
             }

             .basic-table--table th:first-child,
             .basic-table--table td:first-child {
                text-align: right;
             }
        </style>
    </head>
    <body>
        <h1>TimeBoundary data for DataSource "<b>$druidDataSource</b>": </h1>
        <table class="basic-table--table">
            <thead>
                <tr>
                    <th>DataSource</th>
                    <th>Start</th>
                    <th>End</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>$druidDataSource</td>
                    <td>$formattedStartTime</td>
                    <td>$formattedEndTime</td>
                </tr>
            </tbody>
        </table>
        <div>
            <h1>Raw Group By Query Results</h1>
            <table class="basic-table--table">
                <thead>
                    $groupByHeadRows
                </thead>
                <tbody>
                    $groupByBodyRows
                </tbody>
            </table>
        </div>
    </body>
</html>

HTML_BODY;
