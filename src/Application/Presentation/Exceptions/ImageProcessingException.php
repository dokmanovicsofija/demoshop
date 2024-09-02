<?php

namespace Application\Presentation\Exceptions;

use Exception;

/**
 * Class ImageProcessingException
 *
 * This exception is thrown when there is an error during the image processing.
 */
class ImageProcessingException extends Exception
{
    /**
     * Constructor for ImageProcessingException.
     *
     * @param string $message The message that describes the reason for the exception.
     * @param int $code The exception code 400.
     */
    public function __construct(string $message = "Error processing image", int $code = 400)
    {
        parent::__construct($message, $code);
    }
}