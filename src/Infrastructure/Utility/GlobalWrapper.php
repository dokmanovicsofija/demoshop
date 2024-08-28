<?php

namespace Infrastructure\Utility;

use Exception;

class GlobalWrapper
{
    /**
     * Get a global array by name.
     *
     * @param string $globalName The name of the global array.
     * @return array The global array.
     * @throws Exception If the global array does not exist.
     */
    public static function getGlobal(string $globalName): array
    {
        return match ($globalName) {
            '_GET' => $_GET,
            '_POST' => $_POST,
            '_SERVER' => $_SERVER,
            '_REQUEST' => $_REQUEST,
            '_FILES' => $_FILES,
            '_ENV' => $_ENV,
            '_COOKIE' => $_COOKIE,
            default => throw new Exception("Global variable $globalName does not exist."),
        };
    }

    /**
     * Get all headers from the global request.
     *
     * @return array An associative array of all the headers.
     */
    public static function getAllHeaders(): array
    {
        return getallheaders();
    }

    /**
     * Set a cookie.
     *
     * @param string $name The name of the cookie.
     * @param string $value The value of the cookie.
     * @param int $expiry The time the cookie expires (in seconds).
     * @param string $path The path on the server in which the cookie will be available on.
     */
    public static function setCookie(string $name, string $value, int $expiry, string $path = '/'): void
    {
        setcookie($name, $value, $expiry, $path);
    }

    /**
     * Get a cookie value.
     *
     * @param string $name The name of the cookie to retrieve.
     * @return mixed|null The value of the cookie, or null if not set.
     */
    public static function getCookie(string $name): mixed
    {
        return $_COOKIE[$name] ?? null;
    }

    /**
     * Unset a cookie.
     *
     * @param string $name The name of the cookie to unset.
     * @param string $path The path on the server in which the cookie was available.
     */
    public static function unsetCookie(string $name, string $path = '/'): void
    {
        setcookie($name, '', time() - 3600, $path);
    }
}