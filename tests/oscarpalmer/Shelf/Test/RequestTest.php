<?php

namespace oscarpalmer\Shelf\Test;

use oscarpalmer\Shelf\Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    # Mock Request.
    protected $request;

    public function setUp()
    {
        $this->request = new Request(array(
            "REQUEST_METHOD" => "GET",
            "REQUEST_URI" => "/path",
            "SERVER_PROTOCOL" => "HTTP/1.0"
        ));
    }

    public function testConstructor()
    {
        $this->assertNotNull($this->request);
        $this->assertInstanceOf("oscarpalmer\Shelf\Request", $this->request);
    }

    /**
     * @covers \oscarpalmer\Shelf\Request::__get
     * @covers \oscarpalmer\Shelf\Request::setPathInfo
     */
    public function testMagicalGet()
    {
        $request = $this->request;

        $this->assertSame("/path", $request->path_info);
        $this->assertSame("HTTP/1.0", $request->protocol);
        $this->assertSame("GET", $request->request_method);

        $this->assertNull($request->server_admin);
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