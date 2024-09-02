<?php

namespace Application\Presentation\Exceptions;

use Exception;

/**
 * Class EmptyProductIdArrayException
 *
 * This exception is thrown when the product ID array is empty.
 */
class EmptyProductIdArrayException extends Exception
{
    /**
     * Constructor for EmptyProductIdArrayException.
     *
     * @param string $message The message that describes the reason for the exception.
     * @param int $code The exception code 400.
     */
    public function __construct(string $message = "Product ID array is empty", int $code = 400)
    {
        parent::__construct($message, $code);
    }
}