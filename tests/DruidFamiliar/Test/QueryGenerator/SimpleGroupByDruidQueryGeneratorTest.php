<?php

namespace DruidFamiliar\Test\QueryGenerator;

use DruidFamiliar\QueryParameters\SimpleGroupByQueryParameters;
use PHPUnit_Framework_TestCase;

class SimpleGroupByDruidQueryGeneratorTest extends PHPUnit_Framework_TestCase
{

    private $mockDataSourceName = 'my-datasource';

    public function getMockSimpleGroupByQueryParameters()
    {
        $params = new SimpleGroupByQueryParameters();

        $params->setIntervalByStartAndEnd('1981-01-01T4:20', '2012-03-01T3:00');
        $params->granularityType = 'uniform';
        $params->granularity = 'DAY';
        $params->dataSource = $this->mockDataSourceName;
        $params->format = 'json';
        $params->timeDimension = 'date_dim';
        $params->dimensions = array('one_dim', 'two_dim');

        $params->setFilePath('/another/file/path/to/a/file.bebop');

        $params->setFilters(array(
            array('type' => 'or', 'fields' => array(
                array('type' => 'selector', 'dimension' => 'one_dim', 'value' => '10'),
                array('type' => 'selector', 'dimension' => 'one_dim', 'value' => '11')
            ))
        ));

        $params->setAggregators(array(
            array('type' => 'count', 'name' => 'count'),
            array('type' => 'longSum', 'name' => 'total_referral_count', 'fieldName' => 'referral_count')
        ));

        $params->setPostAggregators(array(
            array(
                'type' => 'arithmetic',
                'name' => 'inactive_patients',
                'fn' => '-', // Subtraction operator
                'fields' => array(
                    array('type' => 'fieldAccess', 'fieldName' => 'referral_count'),
                    array('type' => 'fieldAccess', 'fieldName' => 'active_patients'),
                ),
            ),
            array(
                'type' => 'javascript',
                'name' => 'shrinkage',
                'fieldNames' => array("referral_count", "discharged_patients"),
                'function' => 'function(total, discharge) { return 100 * (total /w discharge); }'
            ),
        ));

        return $params;
    }


    public function testGenerateQueryReturnsJSONString()
    {
        $params = $this->getMockSimpleGroupByQueryParameters();

        $q = new \DruidFamiliar\QueryGenerator\SimpleGroupByDruidQueryGenerator();

        $query = $q->generateQuery($params);

        $this->assertJson( $query );
        return $query;
    }


    /**
     * @depends testGenerateQueryReturnsJSONString
     */
    public function testGenerateQueryIncludesDataSource($jsonString)
    {
        $query = json_decode( $jsonString, true );

        $this->assertArrayHasKey('queryType', $query);
        $this->assertArrayHasKey('dataSource', $query, "Generated query must include required parameters.");
    }


    /**
     * @depends testGenerateQueryReturnsJSONString
     */
    public function testGenerateQueryIncludesAggregations($jsonString)
    {

        $query = json_decode( $jsonString, true );

        $this->assertArrayHasKey('aggregations', $query);
        $this->assertCount( 2, $query['aggregations'] );

        $aggs = $query['aggregations'];

        $firstAgg = $aggs[0];
        $secondAgg = $aggs[1];

        $this->assertEquals( "count",                   $firstAgg['type'] );
        $this->assertEquals( "count",                   $firstAgg['name'] );
        $this->assertEquals( "longSum",                 $secondAgg['type'] );
        $this->assertEquals( "total_referral_count",    $secondAgg['name'] );
        $this->assertEquals( "referral_count",       $secondAgg['fieldName'] );
    }

    /**
     * @depends testGenerateQueryReturnsJSONString
     */
    public function testGenerateQueryIncludesFilters($jsonString)
    {

        $query = json_decode( $jsonString, true );

        $this->assertArrayHasKey('filter', $query);
        $firstFilter = $query['filter'];

        $this->assertEquals( "or",          $firstFilter['type'] );
        $this->assertArrayHasKey('fields',  $firstFilter );
        $this->assertCount( 2,              $firstFilter['fields'] );

        $orFilters = $firstFilter['fields'];
        $this->assertCount( 2, $orFilters );

        $firstOrFilter = $orFilters[0];
        $secondOrFilter = $orFilters[1];

        $this->assertEquals( "selector",        $firstOrFilter['type'] );
        $this->assertEquals( "one_dim",       $firstOrFilter['dimension'] );
        $this->assertEquals( "10",  $firstOrFilter['value'] );
        $this->assertEquals( "selector",        $secondOrFilter['type'] );
        $this->assertEquals( "one_dim",       $secondOrFilter['dimension'] );
        $this->assertEquals( "11",  $secondOrFilter['value'] );

    }

    /**
     * @depends testGenerateQueryReturnsJSONString
     */
    public function testGenerateQueryIncludesPostAggregations($jsonString)
    {

        $query = json_decode( $jsonString, true );

        $this->assertArrayHasKey('postAggregations', $query);
        $this->assertCount( 2, $query['postAggregations'] );

        $postAggs = $query['postAggregations'];

        $firstPostAgg = $postAggs[0];
        $secondPostAgg = $postAggs[1];

        $this->assertEquals( "arithmetic",          $firstPostAgg['type'] );
        $this->assertEquals( "inactive_patients",   $firstPostAgg['name'] );
        $this->assertEquals( "-",                   $firstPostAgg['fn'] );
        $this->assertEquals( "fieldAccess",         $firstPostAgg['fields'][0]['type'] );
        $this->assertEquals( "referral_count",      $firstPostAgg['fields'][0]['fieldName'] );
        $this->assertEquals( "fieldAccess",         $firstPostAgg['fields'][1]['type'] );
        $this->assertEquals( "active_patients",     $firstPostAgg['fields'][1]['fieldName'] );

        $this->assertEquals( "javascript",          $secondPostAgg['type'] );
        $this->assertEquals( "shrinkage",           $secondPostAgg['name'] );
        $this->assertCount(  2,                     $secondPostAgg['fieldNames'] );
        $this->assertEquals( "referral_count",      $secondPostAgg['fieldNames'][0] );
        $this->assertEquals( "discharged_patients", $secondPostAgg['fieldNames'][1] );
        $this->assertEquals(
            "function(total, discharge) { return 100 * (total /w discharge); }",
            $secondPostAgg['function']
        );

    }


    public function testGenerateQueryRequiresDataSource()
    {
        try {
            $params = $this->getMockSimpleGroupByQueryParameters();
            $params->dataSource = NULL;

            $q = new \DruidFamiliar\QueryGenerator\SimpleGroupByDruidQueryGenerator();
            $query = $q->generateQuery($params);
        } catch (\DruidFamiliar\Exception\MissingParametersException $e) {
            $this->assertContains('dataSource', $e->missingParameters, "Returned missing parameters: " . join(',', $e->missingParameters));
            return;
        }

        $this->fail('An expected exception was not raised');
    }


    public function testGenerateQueryRequiresQueryType()
    {
        try {
            $params = $this->getMockSimpleGroupByQueryParameters();
            $params->queryType = NULL;

            $q = new \DruidFamiliar\QueryGenerator\SimpleGroupByDruidQueryGenerator($params);
            $query = $q->generateQuery($params);
        } catch (\DruidFamiliar\Exception\MissingParametersException $e) {
            $this->assertContains('queryType', $e->missingParameters);
            return;
        }

        $this->fail('An expected exception was not raised');
    }


    public function testGenerateQueryRequiresIntervals()
    {
        $this->setExpectedException('\DruidFamiliar\Exception\MissingParametersException');

        $params = new SimpleGroupByQueryParameters();

        $params = $this->getMockSimpleGroupByQueryParameters();
        $params->intervals = NULL;

        $q = new \DruidFamiliar\QueryGenerator\SimpleGroupByDruidQueryGenerator($params);
        $q->generateQuery($params);
    }


    public function testGenerateQueryHandlesNotHavingAggregations()
    {
        $params = $this->getMockSimpleGroupByQueryParameters();
        $params->setAggregators(array());

        $q = new \DruidFamiliar\QueryGenerator\SimpleGroupByDruidQueryGenerator($params);

        $query = $q->generateQuery($params);

        $this->assertJson( $query );

        $query = json_decode( $query, true );

        $this->assertArrayNotHasKey('aggregations', $query);
    }


    public function testGenerateQueryHandlesNotHavingPostAggregations()
    {
        $params = $this->getMockSimpleGroupByQueryParameters();
        $params->setPostAggregators(array());

        $q = new \DruidFamiliar\QueryGenerator\SimpleGroupByDruidQueryGenerator($params);

        $query = $q->generateQuery($params);

        $this->assertJson( $query );

        $query = json_decode( $query, true );

        $this->assertArrayNotHasKey('postAggregations', $query);
    }

    public function testGenerateQueryHandlesStringGranularities()
    {
        $params = $this->getMockSimpleGroupByQueryParameters();
        $params->granularity = 'DAY';

        $q = new \DruidFamiliar\QueryGenerator\SimpleGroupByDruidQueryGenerator();

        $query = $q->generateQuery($params);


        $jsonString = json_decode( $query, true );

        $this->assertArrayHasKey('granularity', $jsonString);
        $this->assertEquals( 'DAY', $jsonString['granularity'] );
    }

    public function testGenerateQueryHandlesObjectGranularities()
    {
        $params = $this->getMockSimpleGroupByQueryParameters();
        $params->granularity = array('type'=> "period", "period"=>"P3M");

        $q = new \DruidFamiliar\QueryGenerator\SimpleGroupByDruidQueryGenerator();

        $query = $q->generateQuery($params);


        $jsonString = json_decode( $query, true );

        $this->assertArrayHasKey('granularity', $jsonString);
        $this->assertCount( 2, $jsonString['granularity'] );
        $this->assertArrayHasKey('type', $jsonString['granularity']);
        $this->assertArrayHasKey('period', $jsonString['granularity']);

        $this->assertEquals( 'period', $jsonString['granularity']['type'] );
        $this->assertEquals( 'P3M', $jsonString['granularity']['period'] );
    }
}