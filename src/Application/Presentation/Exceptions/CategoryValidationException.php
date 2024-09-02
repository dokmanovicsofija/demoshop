<?php

namespace Application\Presentation\Exceptions;

use Exception;

/**
 * Class CategoryValidationException
 *
 * Ovaj izuzetak se baca kada validacija kategorije ne uspe.
 */
class CategoryValidationException extends Exception
{
    /**
     * Konstruktor za CategoryValidationException.
     *
     * @param string $message Poruka koja opisuje razlog za izuzetak.
     * @param int $code Kod izuzetka (opciono).
     */
    public function __construct(string $message = "Category validation failed", int $code = 0)
    {
        parent::__construct($message, $code);
    }
}
