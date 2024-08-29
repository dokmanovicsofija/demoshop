<?php

namespace Infrastructure\Utility;

/**
 * Class CookieManager
 *
 * This class provides a simple interface for managing HTTP cookies.
 * It includes methods to set, retrieve, and unset cookies.
 *
 * Cookies are small pieces of data that are stored on the client side
 * and sent back to the server with each HTTP request. They are commonly
 * used for session management, personalization, and tracking.
 */
class CookieManager
{
    /**
     * Set a cookie.
     *
     * @param string $name The name of the cookie.
     * @param string $value The value of the cookie.
     * @param int $expiry The time the cookie expires (in seconds). Default is 0 (end of session).
     * @param string $path The path on the server in which the cookie will be available on. Default is '/'.
     */
    public function setCookie(string $name, string $value, int $expiry = 0, string $path = '/'): void
    {
        setcookie($name, $value, $expiry, $path);
    }

    /**
     * Get a cookie value.
     *
     * @param string $name The name of the cookie to retrieve.
     * @return mixed|null The value of the cookie, or null if not set.
     */
    public function getCookie(string $name): mixed
    {
        return $_COOKIE[$name] ?? null;
    }

    /**
     * Unset a cookie.
     *
     * @param string $name The name of the cookie to unset.
     * @param string $path The path on the server in which the cookie was available. Default is '/'.
     */
    public function unsetCookie(string $name, string $path = '/'): void
    {
        setcookie($name, '', time() - 3600, $path);
    }
}