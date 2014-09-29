<?php

namespace DruidFamiliar\Test\ResponseHandler;

use DruidFamiliar\ResponseHandler\GroupByResponseHandler;
use Guzzle\Http\Message\Response;
use PHPUnit_Framework_TestCase;

/**
 * Class GroupByResponseHandlerTest
 * @package   DruidFamiliar\Test\ResponseHandler
 * @author    Ernesto Spiro Peimbert Andreakis
 * @version   1.0
 * @category  WebPT
 * @copyright Copyright (c) 2014 WebPT, Inc.
 */
class GroupByResponseHandlerTest extends PHPUnit_Framework_TestCase
{
    /**
     * The response handler
     * @access protected
     * @var \DruidFamiliar\ResponseHandler\GroupByResponseHandler
     */
    protected $responseHandler;

    /**
     * Prepares the test
     */
    public function setUp()
    {
        $this->responseHandler = new GroupByResponseHandler();
    }

    /**
     * Tests that a good formed response is properly handled
     */
    public function testTranslatesGoodResponseIntoGroupByResponse()
    {
        $goodResponse    = <<< 'JSONRESPONSE'
[{"version":"v1","timestamp":"2008-01-01T00:00:00.000Z","event":{"referral_id":"10","quantity":1,"group":"2","facility_id":"1"}},{"version":"v1","timestamp":"2008-01-01T00:00:00.000Z","event":{"referral_id":"1002","quantity":1,"group":"2","facility_id":"1"}},{"version":"v1","timestamp":"2008-01-01T00:00:00.000Z","event":{"referral_id":"1011","quantity":1,"group":"2","facility_id":"1"}}]

JSONRESPONSE;
        $mockedResponse  = new Response(200, array(), $goodResponse);
        $handledResponse = $this->responseHandler->handleResponse($mockedResponse);
        $data            = $handledResponse->getData();
        $record          = $data[1];
        $responseCount   = count($data);
        $this->assertEquals(3, $responseCount); //Response has 3 records??
        $this->assertTrue(isset($record['event'])); //Response has an event key??
        $this->assertEquals('v1', $record['version']); //Response version is v1???
        $this->assertEquals('1002', $record['event']['referral_id']); //Is the referral id the same as in the JSON object???
        $this->assertEquals('2', $record['event']['group']); //Is the group the same as in the JSON object???
    }

    /**
     * Tests that a good formed response is properly handled
     */
    public function testTranslatesGoodResponseWithBadRecordIntoGroupByResponse()
    {
        $goodResponseWithBadRecord = <<< 'JSONRESPONSE'
[{"version":"v1","timestamp":"2008-01-01T00:00:00.000Z","event":{"referral_id":"10","quantity":1,"group":"3","facility_id":"1"}},{"fakeObject":"true","willThisAffectBehaviour":"NO"},{"version":"v1","timestamp":"2008-01-01T00:00:00.000Z","event":{"referral_id":"1002","quantity":1,"group":"2","facility_id":"1"}},{"version":"v1","timestamp":"2008-01-01T00:00:00.000Z","event":{"referral_id":"1011","quantity":1,"group":"2","facility_id":"1"}}]

JSONRESPONSE;
        $mockedResponse            = new Response(200, array(), $goodResponseWithBadRecord);
        $handledResponse           = $this->responseHandler->handleResponse($mockedResponse);
        $data                      = $handledResponse->getData();
        $record                    = $data[0];
        $responseCount             = count($data);
        $this->assertEquals(3, $responseCount); //Response has 3 records?? It should because it will strip the bad data and just consume good records
        $this->assertTrue(isset($record['event'])); //Response has an event key??
        $this->assertEquals('v1', $record['version']); //Response version is v1???
        $this->assertEquals('10', $record['event']['referral_id']); //Is the referral id the same as in the JSON object???
        $this->assertEquals('3', $record['event']['group']); //Is the group the same as in the JSON object???
    }
} 