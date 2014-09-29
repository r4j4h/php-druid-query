<?php

namespace DruidFamiliar\Test\QueryParameters;

use DruidFamiliar\QueryParameters\SegmentMetadataQueryParameters;
use DruidFamiliar\QueryParameters\TimeBoundaryQueryParameters;
use PHPUnit_Framework_TestCase;

class TimeBoundaryQueryParametersTest extends PHPUnit_Framework_TestCase
{
    public $mockDataSource = 'mydataSource';

    public function setUp()
    {
        date_default_timezone_set('America/Denver');
    }

    public function testValidate()
    {
        $parametersInstance = new TimeBoundaryQueryParameters($this->mockDataSource);

        $parametersInstance->validate();

        $this->assertEquals($this->mockDataSource, $parametersInstance->dataSource);

        return $parametersInstance;
    }

    /**
     * @depends testValidate
     */
    public function testValidateWithMissingDataSource()
    {
        $parametersInstance = new TimeBoundaryQueryParameters($this->mockDataSource);

        $parametersInstance->dataSource = NULL;

        $this->setExpectedException('DruidFamiliar\Exception\MissingParametersException');

        $parametersInstance->validate();
    }
}