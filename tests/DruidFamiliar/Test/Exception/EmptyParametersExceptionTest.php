<?php

namespace DruidFamiliar\Test\Exception;

use DruidFamiliar\Exception\EmptyParametersException;
use PHPUnit_Framework_TestCase;

/**
 * Class EmptyParametersExceptionTest
 * @package   DruidFamiliar\Test\Exception
 * @author    Ernesto Spiro Peimbert Andreakis
 * @version   1.0
 * @category  WebPT
 * @copyright Copyright (c) 2014 WebPT, Inc.
 */
class EmptyParametersExceptionTest extends PHPUnit_Framework_TestCase{
    /**
     * Tests the empty parameters exception
     */
    public function testIncludesMissingParametersInExceptionMessage()
    {
        $a = new EmptyParametersException(array('param1', 'param2'));

        $msg = $a->getMessage();

        $this->assertContains('Empty parameters', $msg, '', true); // Case insensitive
        $this->assertContains('param1', $msg, '', false); // Case sensitive (they are parameters!)
        $this->assertContains('param2', $msg, '', false); // Case sensitive (they are parameters!)
    }
} 