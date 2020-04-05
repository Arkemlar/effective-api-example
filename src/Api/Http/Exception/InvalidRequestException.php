<?php

namespace App\Api\Http\Exception;

use Exception;
use Throwable;

class InvalidRequestException extends Exception
{
    public function __construct($message = 'Invalid request', $code = null, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}