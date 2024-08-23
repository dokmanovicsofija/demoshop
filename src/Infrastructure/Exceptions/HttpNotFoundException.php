<?php

namespace Infrastructure\Exceptions;

use Exception;

/**
 * Class HttpNotFoundException
 *
 * Represents a "Not Found" HTTP exception, typically used when a requested resource
 * cannot be found.
 */
class HttpNotFoundException extends Exception
{
    /**
     * HttpNotFoundException constructor.
     *
     * @param string $message The exception message.
     * @param int $code The exception code (default is 404).
     * @param Exception|null $previous Optional previous exception for chaining.
     */
    public function __construct(string $message = "Page not found", int $code = 404, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
