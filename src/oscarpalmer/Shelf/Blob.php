<?php

namespace oscarpalmer\Shelf;

/**
 * Blob; a container class.
 */
class Blob
{
    /**
     * @var array $blob Parameter storage.
     */
    protected $blob;

    /**
     * Store an array as a Blob.
     *
     * @param array $blob Array to store.
     */
    public function __construct(array $blob = array())
    {
        $this->blob = $blob;
    }

    /** Public functions. */

    /**
     * Get the actual Blob array.
     *
     * @return array Blob array.
     */
    public function all()
    {
        return $this->blob;
    }

    /**
     * Delete value for key in Blob.
     *
     * @param  mixed $key Key to delete.
     * @return Blob  Blob object for optional chaining.
     */
    public function delete($key)
    {
        if (array_key_exists($key, $this->blob)) {
            unset($this->blob[$key]);
        }

        return $this;
    }

    /**
     * Get the value for a specific key.
     *
     * @param  mixed $key     Key to look for.
     * @param  mixed $default Default value.
     * @return mixed Found or default value.
     */
    public function get($key, $default = null)
    {
        if (array_key_exists($key, $this->blob)) {
            return $this->blob[$key];
        }

        return $default;
    }

    /**
     * Check if Blob has key.
     *
     * @param  mixed $key Key to look for.
     * @return bool  True if found.
     */
    public function has($key)
    {
        return array_key_exists($key, $this->blob);
    }

    /**
     * Set value for key in Blob.
     *
     * @param  mixed $key   Key to set.
     * @param  mixed $value Value for key.
     * @return Blob  Blob object for optional chaining.
     */
    public function set($key, $value)
    {
        $this->blob[$key] = $value;

        return $this;
    }
}
