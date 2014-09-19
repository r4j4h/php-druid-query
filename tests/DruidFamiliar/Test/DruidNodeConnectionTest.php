<?php

namespace DruidFamiliar\Test;

use DruidFamiliar\DruidNodeDruidQueryExecutor;
use DruidFamiliar\QueryGenerator\TimeBoundaryDruidQueryGenerator;
use PHPUnit_Framework_TestCase;

class DruidNodeConnectionTest extends PHPUnit_Framework_TestCase
{

    public function testGetBaseUrlAssemblesCorrectEndpoint()
    {
        $c = new DruidNodeDruidQueryExecutor('1.2.3.4', '1234', '/home/', 'https');
        $b = $c->getBaseUrl();
        $this->assertEquals( 'https://1.2.3.4:1234/home/', $b);
    }

    /**
     * @depends testGetBaseUrlAssemblesCorrectEndpoint
     */
    public function testDefaultsToDruidV2Endpoint()
    {
        $c = new DruidNodeDruidQueryExecutor('1.2.3.4', '1234');
        $b = $c->getBaseUrl();
        $this->assertEquals( 'http://1.2.3.4:1234/druid/v2/', $b);
    }

    /**
     * @depends testGetBaseUrlAssemblesCorrectEndpoint
     */
    public function testDefaultsToHttp()
    {
        $c = new DruidNodeDruidQueryExecutor('1.2.3.4', '1234', '/home/');
        $b = $c->getBaseUrl();
        $this->assertEquals( 'http://1.2.3.4:1234/home/', $b);
    }

    public function testCreateRequest()
    {
        $c = new DruidNodeDruidQueryExecutor('1.2.3.4', '1234', '/mypath/');
        $query = new TimeBoundaryDruidQueryGenerator('some-datasource');

        $req = $c->createRequest( $query );

        $this->assertEquals('1.2.3.4',  $req->getHost()     );
        $this->assertEquals('POST',     $req->getMethod()   );
        $this->assertEquals('/mypath/', $req->getPath()     );
        $this->assertEquals('1234',     $req->getPort()     );
    }


    public function testExecuteQueryPassesResponseToHandleResponse()
    {
        $jsonData = '{"hey":123}';

        // Create fake json response
        $mockResponse = $this->getMockBuilder('MockResponse')
            ->setMethods(array('json'))
            ->getMock();
        $mockResponse->expects($this->once())
            ->method('json')
            ->willReturn($jsonData);

        // Create fake request
        $mockRequest = $this->getMockBuilder('MockRequest')
            ->setMethods(array('send'))
            ->getMock();
        $mockRequest->expects($this->once())
            ->method('send')
            ->willReturn($mockResponse);

        // Create fake connection
        /**
         * @var \DruidFamiliar\DruidNodeDruidQueryExecutor $mockConnection
         */
        $mockConnection = $this->getMockBuilder('\DruidFamiliar\DruidNodeConnection')
            ->setConstructorArgs(array('1.2.3.4', '1234'))
            ->setMethods(array('createRequest'))
            ->getMock();
        $mockConnection->expects($this->once())
            ->method('createRequest')
            ->willReturn( $mockRequest );


        // Create fake query params
        $mockQueryParams = $this->getMockBuilder('DruidFamiliar\Interfaces\IDruidQueryParameters')
            ->getMock();

        // Create fake query generator
        $mockQuery = $this->getMockBuilder('DruidFamiliar\Interfaces\IDruidQueryGenerator')
            ->setMethods(array('generateQuery'))
            ->getMock();
        // Expect it to be called with given query params and return the json body
        $mockQuery->expects($this->once())
            ->method('generateQuery')
            ->with($mockQueryParams)
            ->willReturn($jsonData);

        // Create fake response handler to verify it is called with the returned json body
        $mockResponseHandler = $this->getMockBuilder('DruidFamiliar\Interfaces\IDruidQueryResponseHandler')
            ->setMethods(array('handleResponse'))
            ->getMock();
        $mockResponseHandler->expects($this->once())
            ->method('handleResponse')
            ->with($jsonData);

        $mockConnection->executeQuery( $mockQuery, $mockQueryParams, $mockResponseHandler );
    }

}



