<?php

namespace H2P;

class ConverterFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiateNewConverterShouldWork()
    {
        $options = array(
            'orientation' => Converter\PhantomJS::ORIENTATION_LANDSCAPE,
            'format' => Converter\PhantomJS::FORMAT_A0,
            'zoomFactor' => 2,
            'border' => '3cm',
            'header' => null,
            'footer' => null,
            'allowParseCustomFooter' => false,
            'allowParseCustomHeader' => false,
        );

        $instance = ConverterFactory::factory(array(
            'converter' => 'PhantomJS',
            'options' => $options,
        ));

        $this->assertInstanceOf('H2P\\Converter\\PhantomJS', $instance);
        $this->assertEquals($options, $instance->getOptions());
    }
}
