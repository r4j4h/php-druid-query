<?php

namespace DruidFamiliar\Test\ResponseHandler;

use DruidFamiliar\ResponseHandler\TimeBoundaryResponseHandler;
use Guzzle\Http\Message\Response;
use PHPUnit_Framework_TestCase;

class TimeBoundaryResponseHandlerTest extends PHPUnit_Framework_TestCase
{

    /** @var TimeBoundaryResponseHandler The response object to test */
    protected $responseHandler;

    public function setup()
    {
        $this->responseHandler = new TimeBoundaryResponseHandler();
    }

    public function tearDown()
    {
        unset($this->responseHandler);
    }

    public function testHandlesNonDruidResponse()
    {
        try {
            $mockedResponse = new Response(200, array(), '1');
            $handledResponse = $this->responseHandler->handleResponse($mockedResponse);
            $this->assertEquals($mockedResponse, $handledResponse);
        }
        catch (\Exception $e) {
            if ( $e->getMessage() === "Unexpected response format.") {
                return;
            }
        }

        $this->fail('Did not hit an expected exception');

    }

    public function testHandlesEmptyResponse()
    {

        try {
            $mockedResponse = new Response(200);
            $handledResponse = $this->responseHandler->handleResponse($mockedResponse);
            $this->assertEquals($mockedResponse, $handledResponse);
        }
        catch (\Exception $e) {
            if ( $e->getMessage() === "Unknown data source.") {
                return;
            }
        }

        $this->fail('Did not hit an expected exception. Expected Exception with message "Unknown data source."');

    }


    public function testTranslatesIntoTimeBoundaryResponse()
    {
        $jsonResponse = <<<JSONMOCK
[ {
  "timestamp" : "2013-05-09T18:24:00.000Z",
  "result" : {
    "minTime" : "2013-05-09T18:24:00.000Z",
    "maxTime" : "2013-05-09T18:37:00.000Z"
  }
} ]
JSONMOCK;

        $mockedResponse = new Response( 200, array(), $jsonResponse );
        $handledResponse = $this->responseHandler->handleResponse($mockedResponse);

        $this->assertInstanceOf('DruidFamiliar\Response\TimeBoundaryResponse', $handledResponse);
        $this->assertEquals("2013-05-09T18:24:00.000Z", $handledResponse->minTime);
        $this->assertEquals("2013-05-09T18:37:00.000Z", $handledResponse->maxTime);

    }

}



