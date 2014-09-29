<?php

namespace DruidFamiliar\Test\QueryExecutor;

use DruidFamiliar\QueryExecutor\DruidNodeDruidQueryExecutor;
use DruidFamiliar\QueryGenerator\TimeBoundaryDruidQueryGenerator;
use Guzzle\Http\Message\Response;
use PHPUnit_Framework_TestCase;

class DruidNodeDruidQueryExecutorTest extends PHPUnit_Framework_TestCase
{
    public function testGetBaseUrlAssemblesCorrectEndpoint()
    {
        $c = new DruidNodeDruidQueryExecutor('1.2.3.4', '1234', '/home/', 'https');
        $b = $c->getBaseUrl();
        $this->assertEquals('https://1.2.3.4:1234/home/', $b);
    }

    /**
     * @depends testGetBaseUrlAssemblesCorrectEndpoint
     */
    public function testDefaultsToDruidV2Endpoint()
    {
        $c = new DruidNodeDruidQueryExecutor('1.2.3.4', '1234');
        $b = $c->getBaseUrl();
        $this->assertEquals('http://1.2.3.4:1234/druid/v2/', $b);
    }

    /**
     * @depends testGetBaseUrlAssemblesCorrectEndpoint
     */
    public function testDefaultsToHttp()
    {
        $c = new DruidNodeDruidQueryExecutor('1.2.3.4', '1234', '/home/');
        $b = $c->getBaseUrl();
        $this->assertEquals('http://1.2.3.4:1234/home/', $b);
    }

    public function testCreateRequest()
    {
        $c     = new DruidNodeDruidQueryExecutor('1.2.3.4', '1234', '/mypath/');
        $query = new TimeBoundaryDruidQueryGenerator('some-datasource');

        $req = $c->createRequest($query);

        $this->assertEquals('1.2.3.4', $req->getHost());
        $this->assertEquals('POST', $req->getMethod());
        $this->assertEquals('/mypath/', $req->getPath());
        $this->assertEquals('1234', $req->getPort());
    }

    public function testExecuteQueryPassesResponseToHandleResponse()
    {
        // Create fake json response
        $mockResponse = new Response(200);

        // Create fake request
        $mockRequest = $this->getMockBuilder('MockRequest')->setMethods(array('send'))->getMock();
        $mockRequest->expects($this->once())->method('send')->willReturn($mockResponse);

        // Create fake connection
        $mockDruidQueryExecutor = $this->getMockBuilder('\DruidFamiliar\QueryExecutor\DruidNodeDruidQueryExecutor')->setConstructorArgs(array('1.2.3.4', '1234'))->setMethods(array('createRequest'))->getMock();
        $mockDruidQueryExecutor->expects($this->once())->method('createRequest')->willReturn($mockRequest);
        /**
         * @var \DruidFamiliar\QueryExecutor\DruidNodeDruidQueryExecutor $mockDruidQueryExecutor
         */

        // Create fake query params
        $mockQueryParams = $this->getMockBuilder('DruidFamiliar\Interfaces\IDruidQueryParameters')->getMock();

        // Create fake query generator
        $mockGeneratedQuery = '{"hey":123}';
        $mockQueryGenerator = $this->getMockBuilder('DruidFamiliar\Interfaces\IDruidQueryGenerator')->setMethods(array('generateQuery'))->getMock();
        // Expect it to be called with given query params and return the json body
        $mockQueryGenerator->expects($this->once())->method('generateQuery')->with($mockQueryParams)->willReturn($mockGeneratedQuery);

        // Create fake response handler to verify it is called with the returned json body
        $mockResponseHandler = $this->getMock('DruidFamiliar\Interfaces\IDruidQueryResponseHandler');
        $mockResponseHandler->expects($this->once())->method('handleResponse')->with($mockResponse);

        $mockDruidQueryExecutor->executeQuery($mockQueryGenerator, $mockQueryParams, $mockResponseHandler);
    }
}



