<?php

namespace DruidFamiliar\Test\ResponseHandler;

use DruidFamiliar\ResponseHandler\DoNothingResponseHandler;
use PHPUnit_Framework_TestCase;

class DoNothingResponseHandlerTest extends PHPUnit_Framework_TestCase
{

    public function testDoesNotChangeResponse()
    {
        $responseHandler = new DoNothingResponseHandler();

        $mockedResponse = 1;
        $handledResponse = $responseHandler->handleResponse($mockedResponse);
        $this->assertEquals($mockedResponse, $handledResponse, "Does not try to encode or decode");

        $mockedResponse = "a";
        $handledResponse = $responseHandler->handleResponse($mockedResponse);
        $this->assertEquals($mockedResponse, $handledResponse, "Does not try to encode or decode");

        $mockedResponse = '{"a":1, "b": "c"}';
        $handledResponse = $responseHandler->handleResponse($mockedResponse);
        $this->assertEquals($mockedResponse, $handledResponse);

        $mockedResponse = "text\nwith\nnewlines";
        $handledResponse = $responseHandler->handleResponse($mockedResponse);
        $this->assertEquals($mockedResponse, $handledResponse, "Does not strip newlines");
    }

}



