<?php

namespace DruidFamiliar\Test;

use PHPUnit_Framework_TestCase;

class TimeBoundaryQueryTest extends PHPUnit_Framework_TestCase
{

    public function testTimeBoundaryDruidGenerateQuery()
    {
        $mockDataSourceName = 'referral-test';

        $c = new \DruidFamiliar\DruidNodeConnection('127.0.0.1', '8080');

        $q = new \DruidFamiliar\TimeBoundaryDruidQuery($mockDataSourceName);

        $query = $q->generateQuery();

        $this->assertArrayHasKey('queryType', $query);
        $this->assertArrayHasKey('dataSource', $query, "Generated query must include required parameters.");

        $this->assertEquals( 'timeBoundary', $query['queryType'], "Generated query must have timeBoundary for its queryType.");
        $this->assertEquals( $mockDataSourceName, $query['dataSource'], "Generated query must use provided dataSource.");
    }


    public function testTimeBoundaryDruidQueryReturnsResults()
    {

        $c = new \DruidFamiliar\FakeDruidTimeBoundaryConnection('127.0.0.1', '8080');
        $q = new \DruidFamiliar\TimeBoundaryDruidQuery('referral-test');


        $r = $c->executeQuery($q);

        if ( count($r) === 0 ) {
            throw new \Exception('Tests failed: No response');
        }

        if ( !isset( $r[0]['timestamp'] ) ) {
            throw new \Exception('Tests failed: No timestamp in response');
        }

        if ( !isset( $r[0]['result'] ) ) {
            throw new \Exception('Tests failed: No result in response');
        }

        if ( !isset( $r[0]['result']['minTime'] ) ) {
            throw new \Exception('Tests failed: No minTime in result key in response');
        }

        if ( !isset( $r[0]['result']['maxTime'] ) ) {
            throw new \Exception('Tests failed: No maxTime in result key in response');
        }

        $this->assertArrayHasKey( 'result', $r[0] );


        $this->assertArrayHasKey( 'minTime', $r[0]['result'] );
    }

}



