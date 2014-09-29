<?php

namespace DruidFamiliar\Test\QueryGenerator;


use DruidFamiliar\QueryGenerator\GroupByQueryGenerator;
use DruidFamiliar\QueryParameters\GroupByQueryParameters;
use PHPUnit_Framework_TestCase;
use \Exception;
use \stdClass;

/**
 * Class GroupByQueryGeneratorTest
 * @package   DruidFamiliar\Test\QueryGenerator
 * @author    Ernesto Spiro Peimbert Andreakis
 * @version   1.0
 * @category  WebPT
 * @copyright Copyright (c) 2014 WebPT, Inc.
 */
class GroupByQueryGeneratorTest extends PHPUnit_Framework_TestCase {
    
    /**
     * Test the query generation, "happy path"
     */
    public function testGenerateQuery()
    {
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

        $queryInstance = new GroupByQueryGenerator();
        $expectedQuery = '{"queryType":"groupBy","dataSource":"referral-visit-old-format","dimensions":["facility_id","referral_id","group"],"granularity":"all","filter":{"type":"selector","dimension":"company_id","value":1},"aggregations":[{"type":"longSum","name":"quantity","fieldName":"count"}],"intervals":["2008-01-01T00:00:00.000\/2012-01-03T00:00:00.000"]}';

        $query = $queryInstance->generateQuery($parametersInstance);
        $this->assertEquals($expectedQuery, $query);
    }

    /**
     * @expectedException Exception
     */
    public function testGenerateQueryInstanceofMismatch()
    {
        $paramsInstance = $this->getMock('DruidFamiliar\Interfaces\IDruidQueryParameters');

        $queryInstance = new GroupByQueryGenerator();
        $queryInstance->generateQuery($paramsInstance);
    }
}