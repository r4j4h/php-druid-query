<?php

namespace DruidFamiliar\Test\Exception;

use DruidFamiliar\Exception\MissingParametersException;
use PHPUnit_Framework_TestCase;

class MissingParametersExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testIncludesMissingParametersInExceptionMessage()
    {
        $a = new MissingParametersException(array('param1', 'param2'));

        $msg = $a->getMessage();

        $this->assertContains('Missing parameters', $msg, '', true); // Case insensitive
        $this->assertContains('param1', $msg, '', false); // Case sensitive (they are parameters!)
        $this->assertContains('param2', $msg, '', false); // Case sensitive (they are parameters!)
    }
}