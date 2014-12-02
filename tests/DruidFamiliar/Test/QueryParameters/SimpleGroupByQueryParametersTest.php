<?php

namespace DruidFamiliar\Test\QueryParameters;

use DruidFamiliar\Interval;
use PHPUnit_Framework_TestCase;

class SimpleGroupByQueryParametersTest extends PHPUnit_Framework_TestCase
{

    public function testAdjustTimeIntervalForQueryingSuccessfullyBuildsExclusiveTimes()
    {
        $query = $this->getMockBuilder('DruidFamiliar\QueryParameters\SimpleGroupByQueryParameters')
            ->setMethods(null)
            ->getMock();

        $origStartDateTime = '2014-06-18T12:30:01Z';
        $origEndDateTime = '2014-06-18T01:00:00Z';

        $expectedResults = new Interval('2014-06-18T12:30:01Z', '2014-06-18T01:00:01Z');

        $query->setIntervalForQueryingUsingExclusiveTimes($origStartDateTime, $origEndDateTime);
        $results = $query->getIntervals();
        $this->assertEquals($expectedResults, $results);
    }

}