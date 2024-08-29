<?php

namespace Application\Integration\Exceptions;

/**
 * Class RedirectToLoginException
 *
 * Exception that is thrown when the user is not authenticated and needs to be redirected to the login page.
 */
class RedirectToLoginException extends BaseException
{
    /**
     * Constructor for RedirectToLoginException.
     *
     * @param string $message Exception message (optional).
     * @param int $code Exception code (optional), defaults to HTTP 302 redirect status code.
     */
    public function __construct(string $message = 'User is not authenticated, redirecting to login.', int $code = 302)
    {
        parent::__construct($message, $code);
    }
}