<?php

namespace oscarpalmer\Shelf\Test;

use oscarpalmer\Shelf\Request;
use oscarpalmer\Shelf\Session;

class SessionTest extends \PHPUnit\Framework\TestCase
{
    public function setup()
    {
        $_SESSION = [];
    }

    public function testConstructor()
    {
        $session = new Session(true);

        $this->assertInstanceOf("oscarpalmer\Shelf\Session", $session);
    }

    public function testAll()
    {
        $session = new Session(true);

        $this->assertEmpty($session->all());

        unset($_SESSION);

        $this->assertEmpty($session->all());
    }

    public function testBadSession()
    {
        try {
            $session = new Session(array());
        } catch (\Exception $e) {
            $this->assertInstanceOf("InvalidArgumentException", $e);
        }
    }

    public function testDeleteHasGetAndSet()
    {
        $session = new Session(true);

        $this->assertFalse($session->has("key"));

        $session->set("key", "value");

        $this->assertSame("value", $session->get("key"));

        $this->assertTrue($session->has("key"));

        $session->delete("key");

        $this->assertNull($session->get("key"));
    }

    public function testMultipleSessions()
    {
        $session_one = new Session(true);
        $session_two = new Session(true);

        $this->assertEquals($session_one, $session_two);
    }

    public function testNamedSession()
    {
        unset($_SESSION);

        $session = new Session("my_session");

        $this->assertInstanceOf("oscarpalmer\Shelf\Session", $session);
        $this->assertEmpty($session->all());
    }

    public function testNoSession()
    {
        $session = new Session(false);

        $this->assertInstanceOf("oscarpalmer\Shelf\Session", $session);
        $this->assertEmpty($session->all());
    }
}