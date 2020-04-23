<?php

declare(strict_types=1);

namespace App\Api\Http\Failure;


use Symfony\Component\HttpFoundation\Response;

class PermissionDeniedFailure extends ActionFailure
{
    public const HTTP_STATUS_CODE = Response::HTTP_FORBIDDEN;
}