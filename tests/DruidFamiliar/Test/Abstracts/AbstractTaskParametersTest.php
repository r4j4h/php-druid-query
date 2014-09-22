<?php

namespace DruidFamiliar\Test\Abstracts;

use DruidFamiliar\Abstracts\AbstractTaskParameters;
use DruidFamiliar\Exception\MissingParametersException;
use PHPUnit_Framework_TestCase;

class AbstractTaskParametersTest extends PHPUnit_Framework_TestCase
{

    public function testIsValid()
    {
        /**
         * @var AbstractTaskParameters $stub
         */
        $stub = $this->getMockForAbstractClass('DruidFamiliar\Abstracts\AbstractTaskParameters');
        $stub->expects($this->once())
            ->method('validate')
            ->will($this->returnValue(TRUE));


        $isValid = $stub->isValid();

        $this->assertTrue( $isValid );

    }

    public function testIsNotValid()
    {
        /**
         * @var AbstractTaskParameters $stub
         */
        $stub = $this->getMockForAbstractClass('DruidFamiliar\Abstracts\AbstractTaskParameters');
        $stub->expects($this->once())
            ->method('validate')
            ->will($this->throwException(new MissingParametersException(array('a_missing_parameter_name'))));

        $isValid = $stub->isValid();

        $this->assertFalse( $isValid );
    }

}



