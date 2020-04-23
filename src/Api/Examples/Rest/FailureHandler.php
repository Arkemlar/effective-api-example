<?php

declare(strict_types=1);

namespace App\Api\Examples\Rest;

use App\Api\Action\Contract\Failure\ActionFailureInterface;
use App\Api\Action\Contract\Failure\ApiException;
use App\Api\Action\Contract\FailureHandlerInterface;
use App\Api\Http\Failure\InvalidRequestFailure;
use App\Api\Http\Failure\NotFoundFailure;
use App\Api\Http\Failure\PermissionDeniedFailure;
use App\Api\Http\Failure\ValidationFailure;
use Exception;
use LogicException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class FailureHandler implements FailureHandlerInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    private JsonResponseFormatter $responseFormatter;
    private bool $debugFlag;

    public function __construct(JsonResponseFormatter $responseFormatter, bool $debugFlag)
    {
        $this->responseFormatter = $responseFormatter;
        $this->debugFlag = $debugFlag;
    }

    public function handleValidationFailure(ConstraintViolationListInterface $constraintViolations): JsonResponse
    {
        try {
            $responseData = $this->responseFormatter->formatValidationFailure($constraintViolations);
        } catch (Exception $exception) {
            return $this->handleException($exception);
        }
        return new JsonResponse($responseData, ValidationFailure::HTTP_STATUS_CODE);
    }

    public function handleActionFailure(ActionFailureInterface $failure): JsonResponse
    {
        try {
            switch (true) {
                case $failure instanceof ValidationFailure:
                    $responseData = $this->responseFormatter->formatValidationFailure(
                        $failure->getConstraintViolations(),
                        $failure
                    );
                    return new JsonResponse($responseData, $failure::HTTP_STATUS_CODE);
                    break;
                case $failure instanceof InvalidRequestFailure:
                    $responseData = $this->responseFormatter->formatInvalidRequestFailure($failure);
                    return new JsonResponse($responseData, $failure::HTTP_STATUS_CODE);
                    break;
                case $failure instanceof PermissionDeniedFailure:
                    $responseData = $this->responseFormatter->formatPermissionDeniedFailure($failure);
                    return new JsonResponse($responseData, $failure::HTTP_STATUS_CODE);
                    break;
                case $failure instanceof NotFoundFailure:
                    return new JsonResponse(null, $failure::HTTP_STATUS_CODE);
                    break;
                case $failure instanceof ApiException:
                    throw new LogicException('Action should not return ApiException, it must throw it.');
                default:
                    $responseData = $this->responseFormatter->formatUnspecifiedFailure($failure);
                    return new JsonResponse($responseData, Response::HTTP_BAD_REQUEST);
            }
        } catch (Exception $exception) {
            return $this->handleException($exception);
        }
    }

    public function handleException(Exception $exception): JsonResponse
    {
        if ($this->debugFlag) {
            $responseData = $this->responseFormatter->formatException($exception);
        } else {
            $responseData = null;
        }

        return new JsonResponse($responseData, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}