<?php
/**
 * DruidFamiliar Healthcheck Example
 *
 * Run this via the command line, e.g. `php healthcheck.php`
 * and you will either get "Good to go!" or "Problem encountered :("
 */

require_once('../vendor/autoload.php');
$examplesDir = dirname(__FILE__);
$examplesConfig = require_once($examplesDir . '/_examples-config.php');

$druidHost = $examplesConfig['druid-host'];
$druidPort = $examplesConfig['druid-port'];
$druidDataSource = $examplesConfig['druid-dataSource'];


date_default_timezone_set('America/Denver');

try
{
    $c = new \DruidFamiliar\DruidNodeConnection($druidHost, $druidPort);

    $q = new \DruidFamiliar\TransformingTimeBoundaryDruidQuery($druidDataSource);

    $r = $c->executeQuery($q);

    if ( isset( $r['minTime'] ) ) {
        echo "Good to go!\n";
    } else {
        echo "Problem encountered :(\n";
        echo "Talked to something, but it didn't seem to be druid.";
    }

}
catch ( \Exception $e )
{
    echo "Problem encountered :(\n";

    echo "\n";

    $message = $e->getMessage();

    if ( $message === 'Unexpected response format' ) {
        echo "Problem encountered :(\n";
        echo "Does your Data Source exist?";
    }

    throw $e;
}


