<?php

namespace oscarpalmer\Shelf\Test;

use oscarpalmer\Shelf\Shelf;

class ShelfTest extends \PHPUnit\Framework\TestCase
{
    public function testVersion()
    {
        $this->assertInternalType("string", Shelf::VERSION);
    }
}
