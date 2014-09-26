<?php

namespace DruidFamiliar\Test\QueryParameters;

use DruidFamiliar\QueryParameters\GroupByQueryParameters;
use DruidFamiliar\Exception\MissingParametersException;
use DruidFamiliar\Exception\EmptyParametersException;
use PHPUnit_Framework_TestCase;
use stdClass;

/**
 * Class GroupByQueryParametersTest
 * @package   DruidFamiliar\Test\QueryParameters
 * @author    Ernesto Spiro Peimbert Andreakis
 * @version   1.0
 * @category  WebPT
 * @copyright Copyright (c) 2014 WebPT, Inc.
 */
class GroupByQueryParametersTest extends PHPUnit_Framework_TestCase{
    /**
     * Tests the parameters validation
     */
    public function testValidate(){
        $parametersInstance = new GroupByQueryParameters();
        $parametersInstance->setDataSource('referral-visit-old-format');
        $parametersInstance->setGranularity('all');
        $parametersInstance->setDimensions(array('facility_id','referral_id','group'));
        $filter = new stdClass();
        $filter->type = 'selector';
        $filter->dimension = 'company_id';
        $filter->value = 1;
        $parametersInstance->setFilter($filter);
        $anAggregation = new stdClass();
        $anAggregation->type = 'longSum';
        $anAggregation->name = 'quantity';
        $anAggregation->fieldName = 'count';
        $parametersInstance->setAggregations(array($anAggregation));
        $parametersInstance->setIntervals(array('2008-01-01T00:00:00.000/2012-01-03T00:00:00.000'));
        $valid = $parametersInstance->validate();
        $this->assertTrue($valid);
    }

    /**
     * Missing a required parameter (filter)
     *
     * @expectedException MissingParametersException
     */
    public function testMissingParametersException(){
        $parametersInstance = new GroupByQueryParameters();
        $parametersInstance->setDataSource('referral-visit-old-format');
        $parametersInstance->setGranularity('all');
        $parametersInstance->setDimensions(array('facility_id','referral_id','group'));
        $anAggregation = new stdClass();
        $anAggregation->type = 'longSum';
        $anAggregation->name = 'quantity';
        $anAggregation->fieldName = 'count';
        $parametersInstance->setAggregations(array($anAggregation));
        $parametersInstance->setIntervals(array('2008-01-01T00:00:00.000/2012-01-03T00:00:00.000'));
        $valid = $parametersInstance->validate();
    }

    /**
     * A non empty parameter (granularity)
     *
     * @expectedException EmptyParametersException
     */
    public function testEmptyParametersException(){
        $parametersInstance = new GroupByQueryParameters();
        $parametersInstance->setDataSource('referral-visit-old-format');
        $parametersInstance->setGranularity('');
        $parametersInstance->setDimensions(array('facility_id','referral_id','group'));
        $filter = new stdClass();
        $filter->type = 'selector';
        $filter->dimension = 'company_id';
        $filter->value = 1;
        $parametersInstance->setFilter($filter);
        $anAggregation = new stdClass();
        $anAggregation->type = 'longSum';
        $anAggregation->name = 'quantity';
        $anAggregation->fieldName = 'count';
        $parametersInstance->setAggregations(array($anAggregation));
        $parametersInstance->setIntervals(array('2008-01-01T00:00:00.000/2012-01-03T00:00:00.000'));
        $valid = $parametersInstance->validate();
    }
}