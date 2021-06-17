<?php

declare(strict_types=1);

namespace oscarpalmer\Shelf\Blob;

mb_internal_encoding('UTF-8');

interface IBlob
{
    /**
     * Method outline for retrieving data array
     */
    public function all(): array;

    /**
     * Method outline for deleting value by key
     */
    public function delete(int|string $key): self;

    /**
     * Method outline for retreving value by key
     */
    public function get(int|string $key, mixed $default = null): mixed;

    /**
     * Method outline for checking the existence of key
     */
    public function has(int|string $key): bool;

    /**
     * Method outline for setting value for key
     */
    public function set(int|string $key, mixed $value): self;
}
