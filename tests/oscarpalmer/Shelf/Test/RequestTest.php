<?php

namespace oscarpalmer\Shelf\Test;

use oscarpalmer\Shelf\Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    # Mock AJAX request.
    protected $ajax;

    # Mock Request.
    protected $request;

    public function setUp()
    {
        $array = array(
            "REQUEST_METHOD" => "GET",
            "REQUEST_URI" => "/path",
            "SERVER_PROTOCOL" => "HTTP/1.0"
        );

        $this->request = new Request($array);

        $array["HTTP_X_REQUESTED_WITH"] = "XMLHttpRequest";

        $this->ajax = new Request($array);
    }

    public function testConstructor()
    {
        $this->assertNotNull($this->request);
        $this->assertInstanceOf("oscarpalmer\Shelf\Request", $this->request);

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
        $request = $this->request;

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
        $this->assertFalse($this->request->isAjax());
        $this->assertTrue($this->ajax->isAjax());
    }

    public function testIsVerb()
    {
        $request = $this->request;

        $this->assertFalse($request->isDelete());
        $this->assertTrue($request->isGet());
        $this->assertFalse($request->isHead());
        $this->assertFalse($request->isPost());
        $this->assertFalse($request->isPut());
    }
}
