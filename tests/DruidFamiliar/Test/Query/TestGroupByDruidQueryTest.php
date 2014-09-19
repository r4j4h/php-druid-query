<?php

namespace DruidFamiliar\Test;

use DruidFamiliar\ExampleGroupByQueries\ReferralsByCompanyGroupByQueryParameters;
use DruidFamiliar\QueryParameters\SimpleGroupByQueryParameters;
use PHPUnit_Framework_TestCase;

class TestGroupByDruidQueryTest extends PHPUnit_Framework_TestCase
{
    private $mockDataSourceName = 'my-datasource';

    public function getMockIndexTaskQueryParameters()
    {
        $params = new SimpleGroupByQueryParameters();

        $params->intervalStart = '1981-01-01T4:20';
        $params->intervalEnd = '2012-03-01T3:00';
        $params->granularityType = 'uniform';
        $params->granularity = 'DAY';
        $params->dataSource = $this->mockDataSourceName;
        $params->format = 'json';
        $params->timeDimension = 'date_dim';
        $params->dimensions = array('one_dim', 'two_dim');

        $params->setFilePath('/another/file/path/to/a/file.bebop');
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
        $params = $this->getMockIndexTaskQueryParameters();

        $q = new \DruidFamiliar\QueryGenerator\TestGroupByDruidQueryGenerator();

        $params = new ReferralsByCompanyGroupByQueryParameters('test','2010-01-01','2010-02-01');
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
    public function testGenerateQueryIncludesPostAggregations($jsonString)
    {
        $query = json_decode( $jsonString, true );

        $this->assertArrayHasKey('postAggregations', $query);
        $this->assertCount( 2, $query['postAggregations'] );

        $this->markTestIncomplete('need to test exact post aggs came through');
    }

    // TODO Stub out more tests

    public function testGenerateQueryRequiresDataSource()
    {
        try {
            $params = new ReferralsByCompanyGroupByQueryParameters('test','2010-01-01','2010-02-01');

            $q = new \DruidFamiliar\QueryGenerator\TestGroupByDruidQueryGenerator();
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
            $params = new ReferralsByCompanyGroupByQueryParameters('test','2010-01-01','2010-02-01');
            $q = new \DruidFamiliar\QueryGenerator\TestGroupByDruidQueryGenerator($params);
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

        $q = new \DruidFamiliar\QueryGenerator\TestGroupByDruidQueryGenerator($params);

        $params = new ReferralsByCompanyGroupByQueryParameters('test','2010-01-01','2010-02-01');
        $query = $q->generateQuery($params);

    }


    public function testGenerateQueryHandlesNotHavingAggregations()
    {
        $params = $this->getMockIndexTaskQueryParameters();
        $params->setAggregators(array());

        $q = new \DruidFamiliar\QueryGenerator\TestGroupByDruidQueryGenerator($params);

        $params = new ReferralsByCompanyGroupByQueryParameters('test','2010-01-01','2010-02-01');
        $query = $q->generateQuery($params);

        $this->assertJson( $query );

        $query = json_decode( $query, true );

        $this->assertArrayNotHasKey('aggregations', $query);
    }

    public function testGenerateQueryHandlesNotHavingPostAggregations()
    {
        $params = $this->getMockIndexTaskQueryParameters();
        $params->setPostAggregators(array());

        $q = new \DruidFamiliar\QueryGenerator\TestGroupByDruidQueryGenerator($params);

        $params = new ReferralsByCompanyGroupByQueryParameters('test','2010-01-01','2010-02-01');
        $query = $q->generateQuery($params);

        $this->assertJson( $query );

        $query = json_decode( $query, true );

        $this->assertArrayNotHasKey('postAggregations', $query);
    }


}