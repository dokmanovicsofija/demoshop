<?php

namespace Business\Exceptions;

use Exception;

class AuthorizationException extends BaseException
{
    public function __construct($message = "You are not authorized to access this page", $code = 403, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
