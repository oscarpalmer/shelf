<?php

declare(strict_types=1);

namespace oscarpalmer\Shelf\Files;

mb_internal_encoding('utf-8');

/**
 * File
 */
class File
{
    /**
     * @var int Error code
     */
    protected int $error;

    /**
     * @var string File name
     */
    protected string $name;

    /**
     * @var int File size
     */
    protected int $size;

    /**
     * @var string Temporary file name
     */
    protected string $tmp_name;

    /**
     * @var string File type
     */
    protected string $type;

    /**
     * Constructor
     * 
     * @param string $name File name
     * @param string $name File type
     * @param int $name File size
     * @param string $name Temporary file name
     * @param int $name Error code
     */
    public function __construct(string $name, string $type, int $size, string $tmp_name, int $error)
    {
        $this->error = $error;
        $this->name = $name;
        $this->size = $size;
        $this->tmp_name = $tmp_name;
        $this->type = $type;
    }

    /**
     * Get error code for uploaded file
     * 
     * @return int Error code
     */
    public function getError(): int
    {
        return $this->error;
    }

    /**
     * Get file name for uploaded file
     * 
     * @return int File name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get file size for uploaded file
     * 
     * @return int File size
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * Get temporary file name for uploaded file
     * 
     * @return int Temporary file name
     */
    public function getTemporaryName(): string
    {
        return $this->tmp_name;
    }

    /**
     * Get file type for uploaded file
     * 
     * @return int File type
     */
    public function getType(): string
    {
        return $this->type;
    }
}
