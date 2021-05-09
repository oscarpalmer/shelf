<?php

declare(strict_types=1);

namespace oscarpalmer\Shelf;

/**
 * Blob, a container class
 */
class Blob extends \ArrayObject implements IBlob
{
    /**
     * Get the actual Blob array
     *
     * @return array Blob array
     */
    public function all(): array
    {
        return $this->getArrayCopy();
    }

    /**
     * Delete value for key in Blob
     *
     * @param int|string $key Key to delete
     * @return Blob Blob object for optional chaining
     */
    public function delete(int|string $key): Blob
    {
        if ($this->offsetExists($key)) {
            $this->offsetUnset($key);
        }

        return $this;
    }

    /**
     * Get the value for a specific key
     *
     * @param int|string $key Key to look for
     * @param mixed $default Default value
     * @return mixed Found or default value
     */
    public function get(int|string $key, mixed $default = null): mixed
    {
        if ($this->offsetExists($key)) {
            return $this->offsetGet($key);
        }

        return $default;
    }

    /**
     * Check if Blob has key
     *
     * @param mixed $key Key to look for
     * @return bool True if found
     */
    public function has(int|string $key): bool
    {
        return $this->offsetExists($key);
    }

    /**
     * Set value for key in Blob
     *
     * @param mixed $key Key to set
     * @param mixed $value Value for key
     * @return Blob Blob object for optional chaining
     */
    public function set(int|string $key, mixed $value): Blob
    {
        $this->offsetSet($key, $value);

        return $this;
    }
}
