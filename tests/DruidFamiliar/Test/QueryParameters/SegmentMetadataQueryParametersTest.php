<?php

namespace DruidFamiliar\Test\QueryParameters;

use DruidFamiliar\QueryParameters\SegmentMetadataQueryParameters;
use PHPUnit_Framework_TestCase;

class SegmentMetadataQueryParametersTest extends PHPUnit_Framework_TestCase
{

    public $mockDataSource = 'mydataSource';
    public $mockStartTimeString = '2014-01-01T16:00';
    public $mockEndTimeString = '2015-01-01';

    
    public function setUp()
    {
        date_default_timezone_set('America/Denver');
    }


    public function testValidate()
    {
        $parametersInstance = new SegmentMetadataQueryParameters($this->mockDataSource, $this->mockStartTimeString, $this->mockEndTimeString );

        $parametersInstance->validate();

        $this->assertEquals($this->mockDataSource, $parametersInstance->dataSource);
        $this->assertEquals($this->mockStartTimeString, $parametersInstance->intervalStart);
        $this->assertEquals($this->mockEndTimeString, $parametersInstance->intervalEnd);

        return $parametersInstance;
    }



    /**
     * @depends testValidate
     */
    public function testGetIntervalsValue()
    {
        $parametersInstance = new SegmentMetadataQueryParameters($this->mockDataSource, $this->mockStartTimeString, $this->mockEndTimeString );

        $intervalsValue = $parametersInstance->getIntervalsValue();
        $expectedIntervalsValue = $this->mockStartTimeString . '/' . $this->mockEndTimeString;

        $this->assertEquals( $expectedIntervalsValue, $intervalsValue );

        return $parametersInstance;
    }

    /**
     * @depends testGetIntervalsValue
     */
    public function testGetIntervalsRequiresStartAndEndTimes()
    {
        $parametersInstance = new SegmentMetadataQueryParameters($this->mockDataSource, $this->mockStartTimeString, $this->mockEndTimeString );

        $parametersInstance->intervalEnd = null;

        $this->setExpectedException('DruidFamiliar\Exception\MissingParametersException');

        $i = $parametersInstance->getIntervalsValue();
    }



    /**
     * @depends testValidate
     */
    public function testValidateWithMissingDataSource()
    {
        $parametersInstance = new SegmentMetadataQueryParameters($this->mockDataSource, $this->mockStartTimeString, $this->mockEndTimeString );

        $parametersInstance->dataSource = null;

        $this->setExpectedException('DruidFamiliar\Exception\MissingParametersException');

        $parametersInstance->validate();
    }
    /**
     * @depends testValidate
     */
    public function testValidateWithMissingStartInterval()
    {
        $parametersInstance = new SegmentMetadataQueryParameters($this->mockDataSource, $this->mockStartTimeString, $this->mockEndTimeString );

        $parametersInstance->intervalStart = null;

        $this->setExpectedException('DruidFamiliar\Exception\MissingParametersException');

        $parametersInstance->validate();
    }
    /**
     * @depends testValidate
     */
    public function testValidateWithMissingEndInterval()
    {
        $parametersInstance = new SegmentMetadataQueryParameters($this->mockDataSource, $this->mockStartTimeString, $this->mockEndTimeString );

        $parametersInstance->intervalEnd = null;

        $this->setExpectedException('DruidFamiliar\Exception\MissingParametersException');

        $parametersInstance->validate();
    }


}