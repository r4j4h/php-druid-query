<?php

namespace DruidFamiliar\Test;

use DateTime;
use DruidFamiliar\DruidTime;
use DruidFamiliar\Interval;
use PHPUnit_Framework_TestCase;

class DruidTimeTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        date_default_timezone_set('UTC');
    }

    public function test__ToStringUsesFormatTimeForDruid()
    {
        $mockInterval = $this->getMock('DruidFamiliar\DruidTime', array('formatTimeForDruid'), array(new \DateTime('2014-01-01T04:20')));

        $mockInterval
            ->expects($this->once())
            ->method('formatTimeForDruid');

        /**
         * @var Interval $mockInterval
         */
        $mockInterval->__toString();
    }

    public function testFormatTimeForDruid()
    {
        $interval = new DruidTime(new DateTime('2014-01-01'));
        $this->assertEquals('2014-01-01T00:00:00Z', $interval->formatTimeForDruid());

        $interval = new DruidTime(new DateTime('2014-01-01 04:20'));
        $this->assertEquals('2014-01-01T04:20:00Z', $interval->formatTimeForDruid());

        $interval = new DruidTime(new DateTime('2014-01-01T04:20'));
        $this->assertEquals('2014-01-01T04:20:00Z', $interval->formatTimeForDruid());
    }

    /**
     * Standard date time strings will default to local time zone.
     *
     * We want UTC time only. At least the output should not be modified.
     */
    public function testCanUseStandardDateTimeStrings()
    {
        $interval = new DruidTime(new DateTime('2014-01-01 00:00'));

        $this->assertEquals('2014-01-01T00:00:00Z', $interval->formatTimeForDruid());
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
        $interval = new DruidTime(new DateTime('2014-01-01T00:00:10Z'));

        $this->assertEquals('2014-01-01T00:00:10Z', $interval->formatTimeForDruid());
    }

    /**
     * Actual ISO 8601 standard spec includes time zone offsets.
     *
     * Y-m-d\TH:i:sO
     *
     */
    public function testCanUseISO8601DateTimeStrings()
    {
        $interval = new DruidTime(new DateTime('2014-01-01T00:20:00-0000'));

        $this->assertEquals('2014-01-01T00:20:00Z', $interval->formatTimeForDruid());
    }

}