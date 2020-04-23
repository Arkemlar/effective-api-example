<?php

declare(strict_types=1);

namespace App\Api\Examples\Rest;

use App\Api\Action\Contract\Failure\ActionFailureInterface;
use App\Api\Http\Failure\InvalidRequestFailure;
use App\Api\Http\Failure\PermissionDeniedFailure;
use App\Api\Http\Failure\ValidationFailure;
use Exception;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class JsonResponseFormatter
{
    private const INTERNAL_ERROR_CODE = 'f77a41de-1553-4788-a1bf-0a82891c735e';
    private const INTERNAL_ERROR_MESSAGE = 'Request processing failed';

    private NormalizerInterface $normalizer;

    public function __construct(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function formatSuccessResponse(?array $data): array
    {
        return $this->prepareSuccessResponseBody($data);
    }

    public function formatUnspecifiedFailure(ActionFailureInterface $failure): array
    {
        return $this->prepareErrorResponseBody(
            $failure->getCode(),
            $failure->getMessage(),
        );
    }

    /**
     * @throws ExceptionInterface
     */
    public function formatValidationFailure(
        ConstraintViolationListInterface $constraintViolations,
        ValidationFailure $failure = null
    ): array {
        $normalisedConstraintViolations = $this->normalizer->normalize($constraintViolations);

        if (isset($failure)) {
            return $this->prepareErrorResponseBody(
                $failure->getCode(),
                $failure->getMessage(),
                $normalisedConstraintViolations,
            );
        }

        return $this->prepareErrorResponseBody(
            ValidationFailure::CODE,
            ValidationFailure::MESSAGE,
            $normalisedConstraintViolations,
        );
    }

    public function formatInvalidRequestFailure(InvalidRequestFailure $failure): array
    {
        return $this->prepareErrorResponseBody(
            $failure->getCode(),
            $failure->getMessage(),
            $failure->getData(),
            );
    }

    public function formatPermissionDeniedFailure(PermissionDeniedFailure $failure): array
    {
        return $this->prepareErrorResponseBody(
            $failure->getCode(),
            $failure->getMessage(),
            );
    }

    public function formatException(Exception $exception): array
    {
        return $this->prepareErrorResponseBody(
            self::INTERNAL_ERROR_CODE,
            self::INTERNAL_ERROR_MESSAGE,
            [
                'code' => (string) $exception->getCode(),
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
                'previous' => $exception->getPrevious() !== null
                    ? [
                        'code' => $exception->getPrevious(),
                        'message' => $exception->getMessage(),
                        'trace' => $exception->getTraceAsString(),
                    ]
                    : null,
            ]
        );
    }

    private function prepareSuccessResponseBody(?array $result): array
    {
        return [
            'result' => $result,
            'error' => null
        ];
    }

    private function prepareErrorResponseBody(string $code, string $message, array $data = null): array
    {
        return [
            'result' => null,
            'error' => [
                'code' => $code,
                'message' => $message,
                'data' => $data
            ]
        ];
    }
}