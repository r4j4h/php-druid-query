<?php

namespace DruidFamiliar\Test\QueryParameters;

use DruidFamiliar\QueryParameters\SegmentMetadataQueryParameters;
use PHPUnit_Framework_TestCase;

class SegmentMetadataQueryParametersTest extends PHPUnit_Framework_TestCase
{
    public $mockDataSource = 'mydataSource';
    public $mockStartTimeString = '2014-01-01T16:00';
    public $mockEndTimeString = '2015-01-01';
    public $expectedIntervalString = '';
    public $parametersInstance;

    public function setUp()
    {
        date_default_timezone_set('UTC');
        $this->expectedIntervalString = $this->mockStartTimeString . ':00Z/' . $this->mockEndTimeString . 'T00:00:00Z';

        $this->parametersInstance = new SegmentMetadataQueryParameters($this->mockDataSource, $this->mockStartTimeString, $this->mockEndTimeString);
    }

    public function testValidate()
    {
        /**
         * @var SegmentMetadataQueryParameters $parametersInstance
         */
        $parametersInstance = $this->parametersInstance;

        $parametersInstance->validate();

        $this->assertEquals($this->mockDataSource, $parametersInstance->dataSource);
        $this->assertEquals($this->expectedIntervalString, $parametersInstance->intervals);

        return $parametersInstance;
    }

    /**
     * @depends testValidate
     */
    public function testIntervalsValue()
    {
        /**
         * @var SegmentMetadataQueryParameters $parametersInstance
         */
        $parametersInstance = $this->parametersInstance;

        $this->assertInstanceOf('DruidFamiliar\Interval', $parametersInstance->intervals);
        $this->assertEquals($this->expectedIntervalString, $parametersInstance->intervals);

        return $parametersInstance;
    }

    /**
     * @depends testValidate
     */
    public function testValidateWithMissingDataSource()
    {
        /**
         * @var SegmentMetadataQueryParameters $parametersInstance
         */
        $parametersInstance = $this->parametersInstance;

        $parametersInstance->dataSource = NULL;

        $this->setExpectedException('DruidFamiliar\Exception\MissingParametersException');

        $parametersInstance->validate();
    }

    /**
     * @depends testValidate
     */
    public function testValidateWithMissingInterval()
    {
        /**
         * @var SegmentMetadataQueryParameters $parametersInstance
         */
        $parametersInstance = $this->parametersInstance;

        $parametersInstance->intervals = NULL;

        $this->setExpectedException('DruidFamiliar\Exception\MissingParametersException');

        $parametersInstance->validate();
    }

    /**
     * @depends testValidate
     */
    public function testValidateWithMalformedInterval()
    {
        /**
         * @var SegmentMetadataQueryParameters $parametersInstance
         */
        $parametersInstance = $this->parametersInstance;

        $parametersInstance->intervals = 1;

        $this->setExpectedException('DruidFamiliar\Exception\UnexpectedTypeException');

        $parametersInstance->validate();
    }
}