<?php

namespace oscarpalmer\Shelf\Test;

use oscarpalmer\Shelf\Request;
use oscarpalmer\Shelf\Session;

class SessionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $session = new Session(true);

        $this->assertInstanceOf("oscarpalmer\Shelf\Session", $session);

        session_destroy();
    }

    public function testAll()
    {
        $session = new Session(true);
        $this->assertEmpty($session->all());

        session_destroy();
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

        session_destroy();
    }

    public function testMultipleSessions()
    {
        $session_one = new Session(true);

        try {
            $session_two = new Session(true);
        } catch (\Exception $e) {
            $this->assertInstanceOf("PHPUnit_Framework_Error_Notice", $e);
        }

        session_destroy();
    }

    public function testNamedSession()
    {
        $session = new Session("my_session");

        $this->assertInstanceOf("oscarpalmer\Shelf\Session", $session);

        $this->assertEmpty($session->all());

        session_destroy();
    }

    public function testNoSession()
    {
        $session = new Session(false);
        $this->assertInstanceOf("oscarpalmer\Shelf\Session", $session);

        $this->assertNull($session->all());
    }
}