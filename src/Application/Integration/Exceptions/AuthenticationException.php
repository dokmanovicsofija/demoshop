<?php

namespace Application\Integration\Exceptions;

use Exception;

class AuthenticationException extends BaseException
{
    public function __construct($message = "Authentication failed", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
