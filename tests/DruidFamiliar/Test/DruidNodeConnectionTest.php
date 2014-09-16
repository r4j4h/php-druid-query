<?php

namespace DruidFamiliar\Test;

use DruidFamiliar\DruidNodeConnection;
use DruidFamiliar\TimeBoundaryDruidQuery;
use PHPUnit_Framework_TestCase;

class DruidNodeConnectionTest extends PHPUnit_Framework_TestCase
{

    public function testGetBaseUrlAssemblesCorrectEndpoint()
    {
        $c = new DruidNodeConnection('1.2.3.4', '1234', '/home/', 'https');
        $b = $c->getBaseUrl();
        $this->assertEquals( 'https://1.2.3.4:1234/home/', $b);
    }

    /**
     * @depends testGetBaseUrlAssemblesCorrectEndpoint
     */
    public function testDefaultsToDruidV2Endpoint()
    {
        $c = new DruidNodeConnection('1.2.3.4', '1234');
        $b = $c->getBaseUrl();
        $this->assertEquals( 'http://1.2.3.4:1234/druid/v2/', $b);
    }

    /**
     * @depends testGetBaseUrlAssemblesCorrectEndpoint
     */
    public function testDefaultsToHttp()
    {
        $c = new DruidNodeConnection('1.2.3.4', '1234', '/home/');
        $b = $c->getBaseUrl();
        $this->assertEquals( 'http://1.2.3.4:1234/home/', $b);
    }

    public function testCreateRequest()
    {
        $c = new DruidNodeConnection('1.2.3.4', '1234', '/mypath/');
        $query = new TimeBoundaryDruidQuery('some-datasource');

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
        $mockConnection = $this->getMockBuilder('DruidFamiliar\DruidNodeConnection')
            ->setMethods(array('createRequest'))
            ->setConstructorArgs(array('1.2.3.4', '1234'))
            ->getMock();
        $mockConnection->expects($this->once())
            ->method('createRequest')
            ->willReturn( $mockRequest );

        // Create fake query to verify handleResponse is called
        $mockQuery = $this->getMockBuilder('DruidFamiliar\TimeBoundaryDruidQuery')
            ->setMethods(array('handleResponse'))
            ->setConstructorArgs(array('my-datasource'))
            ->getMock();
        $mockQuery->expects($this->once())
            ->method('handleResponse')
            ->with($jsonData);

        $mockConnection->executeQuery( $mockQuery );
    }

}



