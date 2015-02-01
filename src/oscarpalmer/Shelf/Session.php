<?php

namespace oscarpalmer\Shelf;

class Session
{
    /**
     * Start a session.
     *
     * @param bool|string $session True to start session; string for named session.
     */
    public function __construct($session)
    {
        $this->startSession($session);
    }

    /** Public functions. */

    /**
     * Return the raw $_SESSION array.
     *
     * @return array $_SESSION array.
     */
    public function all()
    {
        if (isset($_SESSION)) {
            return $_SESSION;
        }

        return null;
    }

    /**
     * Delete value for key in $_SESSION.
     *
     * @param  mixed   $key Key to delete.
     * @return Session Session object for optional chaining.
     */
    public function delete($key)
    {
        if (array_key_exists($key, $_SESSION)) {
            unset($_SESSION[$key]);
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
        if (array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
        }

        return $default;
    }

    /**
     * Check if $_SERVERS has key.
     *
     * @param  mixed $key Key to look for.
     * @return bool  True if found.
     */
    public function has($key)
    {
        return array_key_exists($key, $_SESSION);
    }

    /**
     * Set value for key in $_SESSION.
     *
     * @param  mixed   $key   Key to set.
     * @param  mixed   $value Value for key.
     * @return Session Session object for optional chaining.
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;

        return $this;
    }

    /** Protected functions. */

    /**
     * Start a session or abort if set.
     *
     * @param bool|string $session True to start session; string for named session.
     */
    protected function startSession($session)
    {
        if (is_bool($session) || is_string($session)) {
            if ($session === false || isset($_SESSION)) {
                return;
            }

            if (is_string($session)) {
                session_name($session);
            }

            return session_start();
        }

        $prefix = "Session variable must be of type \"boolean\" or \"string\", \"";

        throw new \InvalidArgumentException($prefix . gettype($session) . "\" given.");
    }
}
