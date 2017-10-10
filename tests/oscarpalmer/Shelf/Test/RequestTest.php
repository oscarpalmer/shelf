<?php

namespace oscarpalmer\Shelf\Test;

use oscarpalmer\Shelf\Request;

class RequestTest extends \PHPUnit\Framework\TestCase
{
    # Mock AJAX request.
    protected $ajax;

    # Mock Request.
    protected $request;

    public function setUp()
    {
        $_SESSION = [];

        $array = array(
            "REQUEST_METHOD" => "GET",
            "REQUEST_URI" => "/path",
            "SERVER_PROTOCOL" => "HTTP/1.0"
        );

        $this->request = $array;

        $array["HTTP_X_REQUESTED_WITH"] = "XMLHttpRequest";

        $this->ajax = $array;
    }

    public function testConstructor()
    {
        $request = new Request($this->request);

        $this->assertNotNull($request);
        $this->assertInstanceOf("oscarpalmer\Shelf\Request", $request);
    }

    public function testFromGlobals()
    {
        $superGlobalRequest = Request::fromGlobals();

        $this->assertNotNull($superGlobalRequest);
        $this->assertInstanceOf("oscarpalmer\Shelf\Request", $superGlobalRequest);
    }

    /**
     * @covers \oscarpalmer\Shelf\Request::__get
     * @covers \oscarpalmer\Shelf\Request::setPathInfo
     */
    public function testMagicalGet()
    {
        $request = new Request($this->request);

        foreach (array(
            $request->cookies,
            $request->data,
            $request->files,
            $request->query,
            $request->server
        ) as $blob) {
            $this->assertNotNull($blob);
            $this->assertInstanceOf("oscarpalmer\Shelf\Blob", $blob);
        }

        $this->assertSame("/path", $request->path_info);
        $this->assertSame("HTTP/1.0", $request->protocol);
        $this->assertSame("GET", $request->request_method);
        $this->assertNull($request->server_admin);
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
        $this->assertFalse($request->isPost());
        $this->assertFalse($request->isPut());
    }
}
