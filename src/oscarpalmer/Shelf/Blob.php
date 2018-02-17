<?php

declare(strict_types = 1);

namespace oscarpalmer\Shelf;

/**
 * Blob; a container class.
 */
class Blob extends \ArrayObject
{
    /**
     * Get the actual Blob array.
     *
     * @return array Blob array.
     */
    public function all() : array
    {
        return $this->getArrayCopy();
    }

    /**
     * Delete value for key in Blob.
     *
     * @param  mixed $key Key to delete.
     * @return Blob  Blob object for optional chaining.
     */
    public function delete($key) : Blob
    {
        if ($this->offsetExists($key)) {
            $this->offsetUnset($key);
        }

        return $this;
    }

    /**
     * Get the value for a specific key.
     *
     * @param  string $key     Key to look for.
     * @param  mixed  $default Default value.
     * @return mixed  Found or default value.
     */
    public function get(string $key, $default = null)
    {
        if ($this->offsetExists($key)) {
            return $this->offsetGet($key);
        }

        return $default;
    }

    /**
     * Check if Blob has key.
     *
     * @param  mixed $key Key to look for.
     * @return bool  True if found.
     */
    public function has($key) : bool
    {
        return $this->offsetExists($key);
    }

    /**
     * Set value for key in Blob.
     *
     * @param  string $key   Key to set.
     * @param  mixed  $value Value for key.
     * @return Blob   Blob object for optional chaining.
     */
    public function set(string $key, $value) : Blob
    {
        $this->offsetSet($key, $value);

        return $this;
    }
}
