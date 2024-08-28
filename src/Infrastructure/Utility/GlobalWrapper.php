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
}