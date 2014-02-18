<?php

namespace oscarpalmer\Shelf\Test;

use oscarpalmer\Shelf\Shelf;

class ShelfTest extends \PHPUnit_Framework_TestCase
{
    public function testVersion()
    {
        $this->assertInternalType("string", Shelf::VERSION);
        $this->assertStringMatchesFormat("%i\.%i\.%i", Shelf::VERSION);
    }
}