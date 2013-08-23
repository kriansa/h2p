<?php

namespace H2P;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    public function testRequestShouldReturnCorrectSerializedArray()
    {
        $instance = new Request(
            'http://www.google.com/',
            Request::METHOD_POST,
            array('param' => 'value'),
            array('X-Header' => 'value'),
            array(
                new Request\Cookie('Cookie', 'value'),
            )
        );

        $this->assertEquals(array(
            'uri' => 'http://www.google.com/',
            'method' => 'POST',
            'params' => array(
                'param' => 'value',
            ),
            'headers' => array(
                'X-Header' => 'value',
            ),
            'cookies' => array(
                array(
                    'name' => 'Cookie',
                    'value' => 'value',
                    'domain' => NULL,
                    'path' => '/',
                    'httpOnly' => false,
                    'secure' => false,
                    'expires' => NULL,
                ),
            ),
        ), $instance->toArray());
    }
}
