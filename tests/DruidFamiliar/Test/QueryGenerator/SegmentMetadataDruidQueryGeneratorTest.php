<?php

namespace DruidFamiliar\Test\QueryGenerator;

use DruidFamiliar\QueryParameters\SegmentMetadataQueryParameters;
use DruidFamiliar\QueryParameters\TimeBoundaryQueryParameters;
use PHPUnit_Framework_TestCase;

class SegmentMetadataDruidQueryGeneratorTest extends PHPUnit_Framework_TestCase
{

    public function testGenerateQuery()
    {
        $mockDataSourceName = 'referral-test';

        $q = new \DruidFamiliar\QueryGenerator\SegmentMetadataDruidQueryGenerator();
        $p = new SegmentMetadataQueryParameters($mockDataSourceName, '1970-01-01T01:30:00Z', '3030-01-01T01:30:00Z');

        $query = $q->generateQuery($p);

        $query = json_decode( $query, true );

        $this->assertArrayHasKey('queryType', $query);
        $this->assertArrayHasKey('intervals', $query);
        $this->assertArrayHasKey('dataSource', $query, "Generated query must include required parameters.");

        $this->assertEquals( 'segmentMetadata', $query['queryType'], "Generated query must have segmentMetadata for its queryType.");
        $this->assertEquals( $mockDataSourceName, $query['dataSource'], "Generated query must use provided dataSource.");
        $this->assertEquals('1970-01-01T01:30:00Z/3030-01-01T01:30:00Z', $query['intervals'] );
    }

}



