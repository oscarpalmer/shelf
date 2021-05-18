<?php

namespace oscarpalmer\Shelf\Test;

use oscarpalmer\Shelf\Request;
use oscarpalmer\Shelf\Response;

class ResponseTest extends \PHPUnit\Framework\TestCase
{
    protected Request $request;
    protected Response $response;

    public function setUp(): void
    {
        $_SESSION = [];

        $this->response = new Response(
            'Test.',
            200,
            ['Content-Type' => 'text/plain']
        );
    }

    public function testConstructor()
    {
        $this->assertNotNull($this->response);
        $this->assertInstanceOf('oscarpalmer\Shelf\Response', $this->response);
    }

    public function testEmptyResponses()
    {
        # Informational, no-content, and not-modified responses.
        foreach ([100, 101, 102, 103, 204, 304] as $status) {
            $response = new Response('This won\'t be echoed.', $status);

            $response->finish(new Request([]));

            $this->expectOutputString('');
        }
    }

    public function testHeadResponse()
    {
        # HEAD response.
        $response = new Response('This won\'t be echoed.');

        $response->finish(new Request(['REQUEST_METHOD' => 'HEAD']));

        $this->expectOutputString('');
    }

    /**
     * @runInSeparateProcess
     */
    public function testFinish()
    {
        $request = new Request([]);
        $response = $this->response;

        $response->finish($request);

        $this->assertTrue($response->isFinished());
        $this->expectOutputString('Test.');
        $this->expectException(\LogicException::class);

        $response->finish($request);
    }

    public function testGetHeaders()
    {
        $this->assertIsArray($this->response->getHeaders());
        $this->assertCount(1, $this->response->getHeaders());
    }

    public function testGetStatusMessage()
    {
        $this->assertIsString($this->response->getStatusMessage());
        $this->assertSame('200 OK', $this->response->getStatusMessage());

        $this->assertIsString($this->response->getStatusMessage(500));
        $this->assertSame('500 Internal Server Error', $this->response->getStatusMessage(500));
    }

    public function testGetAndSetBody()
    {
        $response = $this->response;

        $this->assertIsString($response->getBody());
        $this->assertSame('Test.', $response->getBody());

        $response->setBody('Hello, world!');

        $this->assertIsString($response->getBody());
        $this->assertSame('Hello, world!', $response->getBody());

        $this->expectException(\TypeError::class);

        $response->setBody([]);
    }

    /**
     * @covers \oscarpalmer\Shelf\Response::getHeader
     * @covers \oscarpalmer\Shelf\Response::setHeader
     * @covers \oscarpalmer\Shelf\Response::setHeaders
     */
    public function testGetAndSetHeaders()
    {
        $response = $this->response;

        $this->assertSame('text/plain', $response->getHeader('Content-Type'));
        $this->assertNull($response->getHeader('Content-Length'));

        $response->setHeaders(['Content-Type' => 'text/html']);

        $this->assertSame('text/html', $response->getHeader('Content-Type'));

        $response->setHeader('Content-Type', null);

        $this->assertNull($response->getHeader('Content-Type'));
    }

    public function testGetAndSetStatus()
    {
        $response = $this->response;

        $this->assertIsInt($response->getStatus());
        $this->assertSame(200, $response->getStatus());

        $response->setStatus(404);

        $this->assertIsInt($response->getStatus());
        $this->assertSame(404, $response->getStatus());
        $this->expectException(\InvalidArgumentException::class);

        $response->setStatus(1234);
    }

    public function testWrite()
    {
        $response = $this->response;

        $this->assertIsString($response->getBody());
        $this->assertSame('Test.', $response->getBody());

        $response->write(' - Shelf');

        $this->assertIsString('string', $response->getBody());
        $this->assertSame('Test. - Shelf', $response->getBody());

        $this->expectException(\TypeError::class);

        $response->write([]);
    }
}
