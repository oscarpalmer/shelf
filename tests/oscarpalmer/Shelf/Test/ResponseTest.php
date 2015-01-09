<?php

namespace oscarpalmer\Shelf\Test;

use oscarpalmer\Shelf\Request;
use oscarpalmer\Shelf\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    protected $request;
    protected $response;

    public function setUp()
    {
        $this->response = new Response(
            "Test.",
            200,
            array("Content-Type" => "text/plain")
        );
    }

    public function testConstructor()
    {
        $this->assertNotNull($this->response);
        $this->assertInstanceOf("oscarpalmer\Shelf\Response", $this->response);
    }

    public function testEmptyResponses()
    {
        # Informational, no-content, and not-modified responses.
        foreach (array(100, 101, 204, 205, 301, 302, 303, 304, 307) as $status) {
            $response = new Response("This won't be echoed.", $status);
            $response->finish(new Request);
            $this->expectOutputString("");
        }

        # HEAD response.
        $response = new Response("This won't be echoed.");
        $response->finish(new Request(array("REQUEST_METHOD" => "HEAD")));
        $this->expectOutputString("");
    }

    public function testFinish()
    {
        $request = new Request;
        $response = $this->response;

        $response->finish($request);

        $this->expectOutputString("Test.");

        try {
            $response->finish($request);
        } catch (\Exception $e) {
            $this->assertInstanceOf("LogicException", $e);
        }
    }

    public function testGetHeaders()
    {
        $this->assertInternalType("array", $this->response->getHeaders());
        $this->assertCount(1, $this->response->getHeaders());
    }

    public function testGetStatusMessage()
    {
        $this->assertInternalType("string", $this->response->getStatusMessage());
        $this->assertSame("200 OK", $this->response->getStatusMessage());
    }

    public function testGetAndSetBody()
    {
        $response = $this->response;

        $this->assertInternalType("string", $response->getBody());
        $this->assertSame("Test.", $response->getBody());

        $response->setBody("Hello, world!");
        $this->assertInternalType("string", $response->getBody());
        $this->assertSame("Hello, world!", $response->getBody());

        try {
            $response->setBody(array());
        } catch (\Exception $e) {
            $this->assertInstanceOf("InvalidArgumentException", $e);
        }
    }

    public function testGetAndSetHeader()
    {
        $response = $this->response;

        $this->assertSame("text/plain", $response->getHeader("Content-Type"));
        $this->assertNull($response->getHeader("Content-Length"));

        $response->setHeader("Content-Type", "text/html");
        $this->assertSame("text/html", $response->getHeader("Content-Type"));

        try {
            $response->setHeader(null, array());
        } catch (\Exception $e) {
            $this->assertInstanceOf("InvalidArgumentException", $e);
        }
    }

    public function testGetAndSetStatus()
    {
        $response = $this->response;

        $this->assertInternalType("integer", $response->getStatus());
        $this->assertSame(200, $response->getStatus());

        $response->setStatus(404);
        $this->assertInternalType("integer", $response->getStatus());
        $this->assertSame(404, $response->getStatus());

        try {
            $response->setStatus(1234);
        } catch (\Exception $e) {
            $this->assertInstanceOf("LogicException", $e);
        }

        try {
            $response->setStatus(null);
        } catch (\Exception $e) {
            $this->assertInstanceOf("InvalidArgumentException", $e);
        }
    }

    public function testWrite()
    {
        $response = $this->response;

        $this->assertInternalType("string", $response->getBody());
        $this->assertSame("Test.", $response->getBody());

        $response->write(" - Shelf");
        $this->assertInternalType("string", $response->getBody());
        $this->assertSame("Test. - Shelf", $response->getBody());

        try {
            $response->write(array());
        } catch (\Exception $e) {
            $this->assertInstanceOf("InvalidArgumentException", $e);
        }
    }
}
