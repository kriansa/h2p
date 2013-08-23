<?php

namespace H2P\Request;

class CookieTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiateShouldWork()
    {
        $instance = new Cookie('Cookie', 'value', 'localhost', '/', true, true, 123456);
        $this->assertEquals(array(
                'name' => 'Cookie',
                'value' => 'value',
                'domain' => 'localhost',
                'path' => '/',
                'httpOnly' => true,
                'secure' => true,
                'expires' => 123456,
        ), $instance->toArray());
    }
}
