<?php

declare(strict_types=1);

namespace oscarpalmer\Shelf\Test;

mb_internal_encoding('utf-8');

use oscarpalmer\Shelf\Blob\Session;

class SessionTest extends \PHPUnit\Framework\TestCase
{
    public function setUp(): void
    {
        $_SESSION = [];
    }

    public function testConstructor()
    {
        $session = new Session(true);

        $this->assertInstanceOf('oscarpalmer\Shelf\Blob\Session', $session);
    }

    /**
     * @runInSeparateProcess
     */
    public function testAll()
    {
        $session = new Session(true);

        $this->assertEmpty($session->all());

        unset($_SESSION);

        $this->assertEmpty($session->all());
    }

    public function testBadSession()
    {
        $this->expectException(\TypeError::class);

        new Session([]);
    }

    /**
     * @runInSeparateProcess
     */
    public function testDeleteHasGetAndSet()
    {
        $session = new Session(true);

        $this->assertFalse($session->has('key'));

        $session->set('key', 'value');

        $this->assertSame('value', $session->get('key'));
        $this->assertTrue($session->has('key'));

        $session->delete('key');

        $this->assertNull($session->get('key'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testMultipleSessions()
    {
        $session_one = new Session(true);
        $session_two = new Session(true);

        $this->assertEquals($session_one, $session_two);
    }

    /**
     * @runInSeparateProcess
     */
    public function testNamedSession()
    {
        unset($_SESSION);

        $session = new Session('my_session');

        $this->assertInstanceOf('oscarpalmer\Shelf\Blob\Session', $session);
        $this->assertEmpty($session->all());
    }

    /**
     * @runInSeparateProcess
     */
    public function testNoSession()
    {
        $session = new Session(false);

        $this->assertInstanceOf('oscarpalmer\Shelf\Blob\Session', $session);
        $this->assertEmpty($session->all());
    }
}
