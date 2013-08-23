<?php

namespace H2P;

class TempFileTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiateShouldCreateNewFile()
    {
        $string = 'string to the test file';
        $instance = new TempFile($string);

        $this->assertInstanceOf('H2P\\TempFile', $instance);
        $this->assertFileExists($instance->getFileName());
        $this->assertStringEqualsFile($instance->getFileName(), $string);
    }

    public function testDestructShouldEraseFile()
    {
        $instance = new TempFile();
        $fileName = $instance->getFileName();

        $this->assertFileExists($fileName);
        unset($instance);
        $this->assertFileNotExists($fileName);
    }
}
