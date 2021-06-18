<?php

declare(strict_types=1);

namespace oscarpalmer\Shelf\Test;

use PHPUnit\Framework\TestCase;

use oscarpalmer\Shelf\Request;

class FilesTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testFiles()
    {
        $_FILES = [
            'single' => ['name' => 'a name', 'type' => 'a type', 'size' => 123, 'tmp_name' => 'a tmp', 'error' => 456],
            'multiple' => [
                'name' => ['b1', 'b2'],
                'type' => ['b1', 'b2'],
                'size' => [11, 12],
                'tmp_name' => ['b1', 'b2'],
                'error' => [11, 12],
            ],
        ];

        $request = Request::fromGlobals(false);
        $files = $request->getFiles();

        $this->assertInstanceOf('\oscarpalmer\Shelf\Files\Files', $files);

        $this->assertInstanceOf('\oscarpalmer\Shelf\Files\File', $files->single);
        $this->assertIsArray($files->multiple);

        foreach ($files->multiple as $file) {
            $this->assertInstanceOf('\oscarpalmer\Shelf\Files\File', $file);
        }

        $this->assertSame('a name', $files->single->getName());
        $this->assertSame('a type', $files->single->getType());
        $this->assertSame(123, $files->single->getSize());
        $this->assertSame('a tmp', $files->single->getTemporaryName());
        $this->assertSame(456, $files->single->getError());

        $this->assertNull($files->get('not a file'));

        $_FILES = [];
    }
}
