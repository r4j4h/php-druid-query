<?php

namespace DruidFamiliar\Test\QueryParameters;

use DruidFamiliar\QueryParameters\SegmentMetadataQueryParameters;
use PHPUnit_Framework_TestCase;

class SegmentMetadataQueryParametersTest extends PHPUnit_Framework_TestCase
{


    public function testValidate()
    {
        $mockDataSource = 'mydataSource';
        date_default_timezone_set('America/Denver');

        $p = new SegmentMetadataQueryParameters($mockDataSource);

        $this->assertEquals($mockDataSource, $p->dataSource);


    }

    /**
     * @depends testValidate
     */
    public function testGetIntervalsValue()
    {
        $p = new SegmentMetadataQueryParameters('a', '2014-01-01T16:00', '2015-01-01');

        $i = $p->getIntervalsValue();

        $expected = '2014-01-01T16:00/2015-01-01';

        $this->assertEquals( $expected, $i );
    }

    /**
     * @depends testGetIntervalsValue
     */
    public function testGetIntervalsRequiresStartAndEndTimes()
    {
        $p = new SegmentMetadataQueryParameters('a', '2014-01-01T16:00', '2015-01-01');

        $p->intervalEnd = null;

        $this->setExpectedException('DruidFamiliar\Exception\MissingParametersException');

        $i = $p->getIntervalsValue();
    }


}