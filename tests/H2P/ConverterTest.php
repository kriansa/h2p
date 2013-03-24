<?php

namespace H2P;

use H2P\Adapter\PhantomJS;

class AgenteTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiationShouldWork()
    {
        $tmpFile = new TempFile();
        $adapter = new PhantomJS();
        $converter = new Converter($adapter, $tmpFile, $tmpFile);
        $this->assertInstanceOf('H2P\\TempFile', $tmpFile);
        $this->assertInstanceOf('H2P\\Adapter\\PhantomJS', $adapter);
        $this->assertInstanceOf('H2P\\Converter', $converter);
    }

    public function testConvertUrlInputToPdf()
    {
        $output = new TempFile();
        $instance = new Converter(new PhantomJS(), 'http://www.google.com/', $output);
        $instance->convert();
        $this->assertFileExists($output->getFileName());
    }
}