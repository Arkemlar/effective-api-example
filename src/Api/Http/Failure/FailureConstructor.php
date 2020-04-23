<?php

declare(strict_types=1);

namespace App\Api\Http\Failure;

use Symfony\Component\Validator\ConstraintViolationListInterface;

final class FailureConstructor
{
    public static function validationFailed(ConstraintViolationListInterface $constraintViolations): ValidationFailure
    {
        return new ValidationFailure($constraintViolations);
    }

    public static function invalidRequest(string $code, string $message, array $data = null): InvalidRequestFailure
    {
        return new InvalidRequestFailure($code, $message, $data);
    }

    public static function permissionDenied(): PermissionDeniedFailure
    {
        return new PermissionDeniedFailure('54595901-6c2d-45ad-b6af-9c1777f28a14', 'Permission denied');
    }

    public static function notFound(): NotFoundFailure
    {
        return new NotFoundFailure('6449b3c3-9b34-4f8e-8d63-1330ca2bfbd7', 'Not found');
    }
}