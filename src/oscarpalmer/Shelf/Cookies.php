<?php

declare(strict_types=1);

namespace oscarpalmer\Shelf;

class Cookies implements IBlob
{
    /**
     * Return the raw $_COOKIE array
     *
     * @return array $_COOKIE array
     */
    public function all(): array
    {
        return $_COOKIE ?? [];
    }

    /**
     * Delete value for key in $_COOKIE
     *
     * @param int|string $key Key to delete
     * @return Cookies Cookies object for optional chaining
     */
    public function delete(int|string $key): Cookies
    {
        setcookie($key, '', time() - 1);

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
        return $_COOKIE[$key] ?? $default;
    }

    /**
     * Check if $_COOKIE has key
     *
     * @param int|string $key Key to look for
     * @return bool True if found
     */
    public function has(int|string $key): bool
    {
        return array_key_exists($key, $_COOKIE);
    }

    /**
     * Set value for key in $_COOKIE
     *
     * @param int|string $key Key to set
     * @param mixed $value Value for key
     * @param int $expire Expiry time in seconds
     * @return Session Session object for optional chaining
     */
    public function set(int|string $key, mixed $value, int $expire = 30): Cookies
    {
        setcookie($key, $value, time() + $expire);

        return $this;
    }
}
