<?php

namespace DruidFamiliar\Test;

use DruidFamiliar\ResponseHandler\TimeBoundaryResponseHandler;
use PHPUnit_Framework_TestCase;

class TimeBoundaryResponseHandlerTest extends PHPUnit_Framework_TestCase
{

    public function testHandlesNonDruidResponse()
    {
        try {
            $responseHandler = new TimeBoundaryResponseHandler();
            $mockedResponse = 1;
            $handledResponse = $responseHandler->handleResponse($mockedResponse);
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
            $responseHandler = new TimeBoundaryResponseHandler();
            $mockedResponse = "";
            $handledResponse = $responseHandler->handleResponse($mockedResponse);
            $this->assertEquals($mockedResponse, $handledResponse);
        }
        catch (\Exception $e) {
            if ( $e->getMessage() === "Unknown data source.") {
                return;
            }
        }

        $this->fail('Did not hit an expected exception');

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

        $responseHandler = new TimeBoundaryResponseHandler();
        $mockedResponse = json_decode($jsonResponse, true);
        $handledResponse = $responseHandler->handleResponse($mockedResponse);

        $this->assertInstanceOf('DruidFamiliar\Response\TimeBoundaryResponse', $handledResponse);
        $this->assertEquals("2013-05-09T18:24:00.000Z", $handledResponse->minTime);
        $this->assertEquals("2013-05-09T18:37:00.000Z", $handledResponse->maxTime);

    }

}



