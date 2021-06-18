<?php

declare(strict_types=1);

namespace oscarpalmer\Shelf\Files;

mb_internal_encoding('utf-8');

/**
 * Files
 */
class Files
{
    /**
     * @var array Array of files
     */
    private array $files;

    /**
     * Constructor
     * 
     * @param array $files Original files array
     */
    public function __construct(array $files)
    {
        $this->createArray($files);
    }

    /**
     * Magical getter for files based on name
     * 
     * @return array|File|null Files
     */
    public function __get(string $name): array|File|null
    {
        return $this->get($name);
    }

    /**
     * Getter for file based on name
     */
    public function get(string $name): array|File|null
    {
        if (array_key_exists($name, $this->files)) {
            return $this->files[$name];
        }

        return null;
    }

    /**
     * Create array of File-objects based on uploaded file information
     * 
     * @param array $files Original files
     */
    protected function createArray(array $files): void
    {
        $array = [];

        foreach ($files as $name => $file) {
            if (is_array($file['name'])) {
                $sub_array = [];

                foreach ($file['name'] as $index => $_) {
                    $sub_array[] = new File($file['name'][$index], $file['type'][$index], $file['size'][$index], $file['tmp_name'][$index], $file['error'][$index]);
                }

                $array[$name] = $sub_array;
            } else {
                $array[$name] = new File($file['name'], $file['type'], $file['size'], $file['tmp_name'], $file['error']);
            }
        }

        $this->files = $array;
    }
}
