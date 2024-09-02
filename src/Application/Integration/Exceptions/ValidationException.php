<?php

namespace Application\Integration\Exceptions;

use Exception;

/**
 * Class ValidationException
 *
 * This exception is thrown when input validation fails.
 */
class ValidationException extends Exception
{
    /**
     * Constructs a new ValidationException.
     *
     * @param string $message The validation error message.
     * @param int $code The error code (default is 0).
     */
    public function __construct(string $message, int $code = 0)
    {
        parent::__construct($message, $code);
    }
}