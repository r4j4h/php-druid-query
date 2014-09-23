<?php

namespace DruidFamiliar\Test;

use DruidFamiliar\Interval;
use PHPUnit_Framework_TestCase;

class IntervalTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        date_default_timezone_set('UTC');
    }



    public function test__ToStringUsesGetIntervalsString()
    {
        $mockInterval = $this->getMock('DruidFamiliar\Interval', array('getIntervalsString'), array('2014-01-01T04:20', '2015-01-01T4:20'));

        $mockInterval
            ->expects($this->once())
            ->method('getIntervalsString');

        /**
         * @var Interval $mockInterval
         */
        $mockInterval->__toString();

    }

    public function testGetIntervalsString()
    {
        $interval = new Interval('2014-01-01', '2015-01-01');
        $this->assertEquals('2014-01-01T00:00:00Z/2015-01-01T00:00:00Z', $interval->getIntervalsString());

        $interval = new Interval('2014-01-01 04:20', '2015-01-01 4:20');
        $this->assertEquals('2014-01-01T04:20:00Z/2015-01-01T04:20:00Z', $interval->getIntervalsString());

        $interval = new Interval('2014-01-01T04:20', '2015-01-01T4:20');
        $this->assertEquals('2014-01-01T04:20:00Z/2015-01-01T04:20:00Z', $interval->getIntervalsString());
    }

    /**
     * Standard date time strings will default to local time zone.
     *
     * We want UTC time only. At least the output should not be modified.
     */
    public function testCanUseStandardDateTimeStrings()
    {
        $interval = new Interval('2014-01-01 00:00', '2015-01-01 04:20');

        $this->assertEquals('2014-01-01T00:00:00Z/2015-01-01T04:20:00Z', $interval->getIntervalsString());
    }

    /**
     * Common ISO style seen on many web services, including Druid.
     *
     * Take ISO, replace time zone offsets with 'Z'. We always work in Zulu/UTC/GMT time here.
     *
     * Y-m-d\TH:i:s\Z
     *
     * eBay and Amazon also use this style.
     *
     */
    public function testCanUseDruidDateTimeStrings()
    {
        $interval = new Interval('2014-01-01T00:00:00Z', '2015-01-01T04:20:00Z');

        $this->assertEquals('2014-01-01T00:00:00Z/2015-01-01T04:20:00Z', $interval->getIntervalsString());
    }

    /**
     * Actual ISO 8601 standard spec includes time zone offsets.
     *
     * Y-m-d\TH:i:sO
     *
     */
    public function testCanUseISO8601DateTimeStrings()
    {
        $interval = new Interval('2014-01-01T00:00:00-0000', '2015-01-01T04:20:00-0700');

        $this->assertEquals('2014-01-01T00:00:00Z/2015-01-01T04:20:00Z', $interval->getIntervalsString());
    }



    public function testGetIntervalsStringWithMissingData()
    {
        $interval = new Interval('2014-01-01T00:00:00-0000', '2015-01-01T04:20:00-0700');
        $interval->intervalStart = null;
        $this->setExpectedException('DruidFamiliar\Exception\MissingParametersException', "intervalStart");
        $interval->getIntervalsString();
    }

    public function testGetIntervalsStringWithInvalidData()
    {
        $interval = new Interval('2014-01-01T00:00:00-0000', '2015-01-01T04:20:00-0700');
        $interval->intervalStart = '2014-01-01T00:00:00-0000';
        $this->setExpectedException('DruidFamiliar\Exception\UnexpectedTypeException', "intervalStart");
        $interval->getIntervalsString();
    }

}