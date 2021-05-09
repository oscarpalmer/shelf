<?php

declare(strict_types=1);

namespace oscarpalmer\Shelf;

class Session implements IBlob
{
    /**
     * Start a session
     *
     * @param bool|string $session True to start session, string for named session
     */
    public function __construct($session)
    {
        $this->startSession($session);
    }

    /** Public functions */

    /**
     * Return the raw $_SESSION array
     *
     * @return array $_SESSION array
     */
    public function all(): array
    {
        return $_SESSION ?? [];
    }

    /**
     * Delete value for key in $_SESSION
     *
     * @param int|string $key Key to delete
     * @return Session Session object for optional chaining
     */
    public function delete(int|string $key): Session
    {
        if (array_key_exists($key, $_SESSION)) {
            unset($_SESSION[$key]);
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
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Check if $_SESSION has key
     *
     * @param int|string $key Key to look for
     * @return bool True if found
     */
    public function has(int|string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    /**
     * Set value for key in $_SESSION
     *
     * @param int|string $key Key to set
     * @param mixed $value Value for key
     * @return Session Session object for optional chaining
     */
    public function set(int|string $key, mixed $value): Session
    {
        $_SESSION[$key] = $value;

        return $this;
    }

    /** Protected functions */

    /**
     * Start a session or abort if set
     *
     * @param bool|string $session True to start session, string for named session
     */
    protected function startSession(bool|string $session): void
    {
        if ($session === false || isset($_SESSION)) {
            return;
        }

        if (is_string($session)) {
            session_name($session);
        }

        session_start();
    }
}
