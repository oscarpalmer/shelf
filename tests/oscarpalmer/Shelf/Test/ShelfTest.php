<?php

declare(strict_types=1);

namespace oscarpalmer\Shelf\Test;

mb_internal_encoding('utf-8');

use oscarpalmer\Shelf\Shelf;

class ShelfTest extends \PHPUnit\Framework\TestCase
{
    public function testVersion()
    {
        $this->assertIsString(Shelf::VERSION);
    }
}
