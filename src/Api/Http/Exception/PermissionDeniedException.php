<?php

namespace App\Api\Http\Exception;

use Exception;
use Throwable;

class PermissionDeniedException extends Exception
{
    public function __construct($message = 'Permission denied', $code = null, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}