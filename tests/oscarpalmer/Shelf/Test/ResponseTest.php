<?php

namespace oscarpalmer\Shelf\Test;

use oscarpalmer\Shelf\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    # Mock Response.
    protected $response;

    public function setUp()
    {
        $this->response = new Response(
            200,
            "Test.",
            array("Content-Type" => "text/plain")
        );
    }

    public function testConstructor()
    {
        $this->assertNotNull($this->response);
        $this->assertInstanceOf("oscarpalmer\Shelf\Response", $this->response);
    }

    public function testFinish()
    {
        $response = $this->response;

        $response->finish();

        $this->expectOutputString("Test.");
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
            $this->assertInstanceOf("\InvalidArgumentException", $e);
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
            $this->assertInstanceOf("\InvalidArgumentException", $e);
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
            $this->assertInstanceOf("\LogicException", $e);
        }

        try {
            $response->setStatus(null);
        } catch (\Exception $e) {
            $this->assertInstanceOf("\InvalidArgumentException", $e);
        }
    }
}