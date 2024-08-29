<?php

namespace Infrastructure\Utility;

/**
 * Class SessionManager
 *
 * Singleton class to manage PHP sessions.
 */
class SessionManager extends Singleton
{
    /**
     * SessionManager constructor.
     *
     * Starts the session if it is not already started.
     */
    protected function __construct()
    {
        parent::__construct();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Get a value from the session.
     *
     * @param string $key The key to retrieve.
     * @return mixed|null The value associated with the key, or null if not set.
     */
    public function get(string $key): mixed
    {
        return $_SESSION[$key] ?? null;
    }

    /**
     * Set a value in the session.
     *
     * @param string $key The key to set.
     * @param mixed $value The value to set.
     */
    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Unset a value in the session.
     *
     * @param string $key The key to unset.
     */
    public function unset(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Destroy the session.
     */
    public function destroy(): void
    {
        session_unset();
        session_destroy();
        self::$instances[static::class] = null;
    }
}