<?php

declare(strict_types = 1);

namespace oscarpalmer\Shelf;

interface IBlob
{
    /** Public functions. */

    /**
     * Method outline for retrieving data array.
     */
    public function all();

    /**
     * Method outline for deleting value by key.
     */
    public function delete($key);

    /**
     * Method outline for retreving value by key.
     */
    public function get($key, $default = null);

    /**
     * Method outline for checking the existence of key.
     */
    public function has($key);

    /**
     * Method outline for setting value for key.
     */
    public function set($key, $value);
}
