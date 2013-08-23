<?php

namespace H2P\Converter;

use H2P\Request;
use H2P\TempFile;

class ConverterAbstractTest extends \PHPUnit_Framework_TestCase
{
    public function getMockedConverter()
    {
        $stub = $this->getMockForAbstractClass('H2P\\Converter\\ConverterAbstract');

        $stub->expects($this->any())
            ->method('transform')
            ->will($this->returnValue(true));

        return $stub;
    }

    public function testTransformWithFileShouldWork()
    {
        $origin = 'http://www.google.com';
        $stub = $this->getMockedConverter();
        $stub->convert($origin, new TempFile());
    }

    public function testTransformWithProtectedFileShouldFail()
    {
        $origin = 'http://www.google.com';
        $stub = $this->getMockedConverter();
        $this->setExpectedException('H2P\\Exception', 'The destination file is not writable!');
        $stub->convert($origin, 'http://www.google.com');
    }
}