<?php

declare(strict_types=1);

namespace oscarpalmer\Shelf\Test;

mb_internal_encoding('utf-8');

use oscarpalmer\Shelf\Blob\Blob;

class BlobTest extends \PHPUnit\Framework\TestCase
{
    # Mock Blob.
    protected $blob;

    public function setUp(): void
    {
        $this->blob = new Blob([
            0 => 'alpha',
            1 => 'beta',
            'key' => 'value'
        ]);
    }

    public function testConstructor()
    {
        $this->assertNotNull($this->blob);
        $this->assertInstanceOf('oscarpalmer\Shelf\Blob\Blob', $this->blob);
    }

    public function testDelete()
    {
        $blob = $this->blob;

        $blob->delete(1);

        $this->assertNull($blob->get(1));
    }

    public function testGet()
    {
        $this->assertSame('value', $this->blob->get('key'));
        $this->assertSame('default', $this->blob->get('this-key-doesnt-exist', 'default'));

        $this->assertCount(3, $this->blob->all());
    }

    public function testHas()
    {
        $this->assertTrue($this->blob->has(0));
        $this->assertFalse($this->blob->has('this-key-doesnt-exist'));
    }
}
