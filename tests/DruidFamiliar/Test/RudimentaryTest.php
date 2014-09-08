<?php

namespace DruidFamiliar\Test;

class RudimentaryTest
{

    public function runTest() {

        $c = new \DruidFamiliar\DruidNodeConnection('127.0.0.1', '8080');
        $c = new \DruidFamiliar\FakeDruidTimeBoundaryConnection('127.0.0.1', '8080');

        $q = new \DruidFamiliar\TimeBoundaryDruidQuery('referral-test');

        var_dump( $q->generateQuery() );

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

        var_dump( $r );

    }

}



