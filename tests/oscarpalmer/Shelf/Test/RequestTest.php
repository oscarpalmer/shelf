<?php

declare(strict_types=1);

namespace oscarpalmer\Shelf\Test;

mb_internal_encoding('utf-8');

use oscarpalmer\Shelf\Request;

class RequestTest extends \PHPUnit\Framework\TestCase
{
    # Mock AJAX request.
    protected $ajax;

    # Mock Request.
    protected $request;

    public function setUp(): void
    {
        $_SESSION = [];

        $array = [
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/path',
            'SERVER_PROTOCOL' => 'HTTP/2'
        ];

        $this->request = $array;

        $array['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';

        $this->ajax = $array;
    }

    public function testConstructor()
    {
        $request = new Request($this->request);

        $this->assertNotNull($request);
        $this->assertInstanceOf('oscarpalmer\Shelf\Request', $request);
    }

    public function testFromGlobals()
    {
        $superGlobalRequest = Request::fromGlobals();

        $this->assertNotNull($superGlobalRequest);
        $this->assertInstanceOf('oscarpalmer\Shelf\Request', $superGlobalRequest);
    }

    public function testGetters()
    {
        $request = new Request($this->request);

        foreach ([
            $request->getData(),
            $request->getQuery(),
            $request->getServer()
        ] as $blob) {
            $this->assertNotNull($blob);
            $this->assertInstanceOf('oscarpalmer\Shelf\Blob\Blob', $blob);
        }

        $this->assertInstanceOf('oscarpalmer\Shelf\Blob\Cookies', $request->getCookies());
        $this->assertInstanceOf('oscarpalmer\Shelf\Blob\Session', $request->getSession());

        $this->assertInstanceOf('oscarpalmer\Shelf\Files\Files', $request->getFiles());

        $this->assertSame('/path', $request->getPathInfo());
        $this->assertSame('HTTP/2', $request->getProtocol());
        $this->assertSame('GET', $request->getRequestMethod());
        $this->assertNull($request->getServer()->get('SERVER_ADMIN'));
    }

    public function testIsAjax()
    {
        $ajax = new Request($this->ajax);

        $this->assertTrue($ajax->isAjax());
    }

    public function testIsNotAjax()
    {
        $request = new Request($this->request);

        $this->assertFalse($request->isAjax());
    }

    public function testIsVerb()
    {
        $request = new Request($this->request);

        $this->assertFalse($request->isDelete());
        $this->assertTrue($request->isGet());
        $this->assertFalse($request->isHead());
        $this->assertFalse($request->isOptions());
        $this->assertFalse($request->isPatch());
        $this->assertFalse($request->isPost());
        $this->assertFalse($request->isPut());
    }
}
