<?php

namespace oscarpalmer\Shelf\Test;

use oscarpalmer\Shelf\Shelf;

class ShelfTest extends \PHPUnit_Framework_TestCase
{
    public function testVersion()
    {
        $this->assertInternalType("string", Shelf::VERSION);
        $this->assertStringMatchesFormat("%d.%d.%d", Shelf::VERSION);
    }

    public function testStaticConstructors()
    {
        $request = Shelf::request();
        $this->assertNotNull($request);
        $this->assertInstanceOf("oscarpalmer\Shelf\Request", $request);

        $response = Shelf::response();
        $this->assertNotNull($response);
        $this->assertInstanceOf("oscarpalmer\Shelf\Response", $response);
    }
}