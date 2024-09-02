<?php

namespace Application\Presentation\Exceptions;

use Exception;

/**
 * Class DuplicateSkuException
 *
 * This exception is thrown when attempting to create a product with an SKU that already exists.
 */
class DuplicateSkuException extends Exception
{
    /**
     * Constructor for DuplicateSkuException.
     *
     * @param string $message The message that describes the reason for the exception.
     * @param int $code The exception code 400.
     */
    public function __construct(string $message = "A product with the same SKU already exists.", int $code = 400)
    {
        parent::__construct($message, $code);
    }
}