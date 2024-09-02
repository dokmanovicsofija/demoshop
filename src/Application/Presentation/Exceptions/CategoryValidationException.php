<?php

namespace Application\Presentation\Exceptions;

use Exception;

/**
 * Class CategoryValidationException
 *
 * This exception is thrown when category validation fails.
 */
class CategoryValidationException extends Exception
{
    /**
     * Constructor for CategoryValidationException.
     *
     * @param string $message The message that describes the reason for the exception.
     * @param int $code The exception code (optional).
     */
    public function __construct(string $message = "Category validation failed", int $code = 0)
    {
        parent::__construct($message, $code);
    }
}