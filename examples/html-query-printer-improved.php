<?php
/**
 * DruidFamiliar HTML TimeBoundary Printer Example
 *
 * Point your browser at this through a web server and you should see nicely formatted time boundary data. :)
 */

require_once('../vendor/autoload.php');
$examplesDir = dirname(__FILE__);
$examplesConfig = require_once($examplesDir . '/_examples-config.php');

$druidHost = $examplesConfig['druid-host'];
$druidPort = $examplesConfig['druid-port'];
$druidDataSource = $examplesConfig['druid-dataSource'];

date_default_timezone_set('America/Denver');

$c = new \DruidFamiliar\DruidNodeConnection($druidHost, $druidPort);

$q = new \DruidFamiliar\TransformingTimeBoundaryDruidQuery($druidDataSource);

$r = $c->executeQuery($q);

$q2 = new \DruidFamiliar\ExampleGroupByQueries\ReferralsByCompanyGroupByWithResponseObject();
$q2->setParams(array(
    'dataSource' => $druidDataSource,
    'startInterval' => '2006-01-01T00:00',
    'endInterval' => '2015-01-01T00'
));

$r2 = $c->executeQuery($q2);

// $r =
// array(2) {
//     ["minTime"]=>
//   string(24) "2011-06-01T00:00:11.000Z"
//     ["maxTime"]=>
//   string(24) "2011-11-30T23:55:34.000Z"
// }


$startTime = new DateTime( $r['minTime'] );
$endTime = new DateTime( $r['maxTime'] );

$formattedStartTime = $startTime->format("F m, Y h:i:s A");
$formattedEndTime = $endTime->format("F m, Y h:i:s A");

$groupByBodyRows = '';

foreach ( $r2 as $index => $val)
{
    /**
     * @var \DruidFamiliar\ExampleResponseObjects\ExampleReferralByCompanyResponseObject $exampleReferralByCompanyResponseObject
     */
    $exampleReferralByCompanyResponseObject = $val;

    $timestamp = $exampleReferralByCompanyResponseObject->getTimestamp();
    $companyId = $exampleReferralByCompanyResponseObject->getCompanyId();
    $facilityId = $exampleReferralByCompanyResponseObject->getFacilityId();
    $referrals = $exampleReferralByCompanyResponseObject->getReferrals();

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
                    <tr>
                        <th>timestamp</th>
                        <th>companyId</th>
                        <th>facilityId</th>
                        <th>referrals</th>
                    </tr>
                </thead>
                <tbody>
                    $groupByBodyRows
                </tbody>
            </table>
        </div>
    </body>
</html>

HTML_BODY;
