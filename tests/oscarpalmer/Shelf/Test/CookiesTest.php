<?php

declare(strict_types=1);

namespace oscarpalmer\Shelf\Test;

mb_internal_encoding('utf-8');

use oscarpalmer\Shelf\Blob\Cookies;

class CookiesTest extends \PHPUnit\Framework\TestCase
{
    public function setup(): void
    {
        $_COOKIE = [];
    }

    public function testConstructor()
    {
        $cookies = new Cookies;

        $this->assertInstanceOf('oscarpalmer\Shelf\Blob\Cookies', $cookies);
    }

    /**
     * @runInSeparateProcess
     */
    public function testAll()
    {
        $cookies = new Cookies;

        $this->assertEmpty($cookies->all());

        $_COOKIE['key'] = 'value';

        $this->assertNotEmpty($cookies->all());
    }

    /**
     * @runInSeparateProcess
     */
    public function testDeleteHasGetAndSet()
    {
        $cookies = new Cookies;

        $this->assertNull($cookies->get('key'));
        $this->assertFalse($cookies->has('key'));

        $cookies->set('key', 'value');

        // Fake the setting of value for key
        $_COOKIE['key'] = 'value';

        $this->assertEquals('value', $cookies->get('key'));
        $this->assertTrue($cookies->has('key'));

        $cookies->delete('key', 'value');

        // Fake deletion of value for key
        $_COOKIE['key'] = '';

        $this->assertEquals('', $cookies->get('key'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testMultipleCookiess()
    {
        $cookies_one = new Cookies;
        $cookies_two = new Cookies;

        $this->assertEquals($cookies_one, $cookies_two);
    }

    /**
     * @runInSeparateProcess
     */
    public function testNoCookies()
    {
        $cookies = new Cookies;

        $this->assertInstanceOf('oscarpalmer\Shelf\Blob\Cookies', $cookies);
        $this->assertEmpty($cookies->all());
    }
}
