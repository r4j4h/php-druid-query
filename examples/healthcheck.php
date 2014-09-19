<?php
/**
 * DruidFamiliar Healthcheck Example
 *
 * Run this via the command line, e.g. `php healthcheck.php`
 * and you will either get "Good to go!" or "Problem encountered :("
 */

use DruidFamiliar\Response\TimeBoundaryResponse;
use DruidFamiliar\QueryParameters\TimeBoundaryQueryParameters;

require_once('../vendor/autoload.php');
$examplesDir = dirname(__FILE__);
$examplesConfig = require_once($examplesDir . '/_examples-config.php');

$druidHost = $examplesConfig['druid-host'];
$druidPort = $examplesConfig['druid-port'];
$druidDataSource = $examplesConfig['druid-dataSource'];


date_default_timezone_set('America/Denver');

try
{
    $c = new \DruidFamiliar\DruidNodeDruidQueryExecutor($druidHost, $druidPort);

    $q = new \DruidFamiliar\QueryGenerator\TimeBoundaryDruidQueryGenerator();

    $params = new TimeBoundaryQueryParameters($druidDataSource);

    $responseHandler = new DruidFamiliar\ResponseHandler\TimeBoundaryResponseHandler();

    /**
     * @var TimeBoundaryResponse $timeBoundaryResponse
     */
    $timeBoundaryResponse = $c->executeQuery($q, $params, $responseHandler);

    echo "DruidFamiliar\n";
    echo "Talking to $druidHost on port $druidPort.\n";

    if ( isset( $timeBoundaryResponse->minTime ) ) {
        echo "Good to go!\n";
    } else {
        echo "Problem encountered :(\n";
        echo "Talked to something, but it didn't seem to be druid.";
    }

}
catch ( Guzzle\Common\Exception\InvalidArgumentException $e ) {

}
catch ( Guzzle\Http\Exception\CurlException $e ) {
    echo "Problem encountered :(\n";
    echo "Am I really pointed at a Druid broker node?\n";
}
catch ( \Exception $e )
{
    $message = $e->getMessage();

    if ( $message === 'Unexpected response format' )
    {
        echo "Problem encountered :(\n";
        echo "Does your Data Source exist?";
    }
    else if ( $message === 'Unknown data source' )
    {
        echo "Should be okay, did you point to a non-existant data source?\n";
    }
    else
    {
        throw $e;
    }

}


