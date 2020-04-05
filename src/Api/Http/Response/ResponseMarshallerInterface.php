<?php

namespace App\Api\Http\Response;

use App\Api\Http\Exception\InvalidRequestException;
use App\Api\Http\Exception\PermissionDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

interface ResponseMarshallerInterface
{
    /**
     * Returns success message with arbitrary data, status code and headers.
     */
    public function successResponse(array $data = null, int $status = Response::HTTP_OK, array $headers = []): Response;

    /**
     * Returns an error response if validation failed for request input data or if ValidationFailedException thrown by API action code.
     * Basic rule presumes revealing validation errors to client so it can show them to its user.
     */
    public function validationFailedResponse(ConstraintViolationListInterface $constraintViolationList): Response;

    /**
     * Returns an error response in case if PermissionDeniedException has been thrown by API action code.
     * Basic rule presumes informing client that he has insufficient permissions for that request, maybe with
     * instructions about actions that client may perform to acquire these permissions.
     */
    public function permissionDeniedResponse(PermissionDeniedException $exception): Response;

    /**
     * Returns an error response in case if InvalidRequestException has been thrown by API action code.
     * Basic rule presumes allow to reveal some helpful information to client about reason why request is treated as invalid.
     */
    public function invalidRequestResponse(InvalidRequestException $exception): Response;

    /**
     * Returns an error response for any exception that is not one of the above.
     * Basic rule is to not reveal any significant information to client in production environment.
     */
    public function internalServerErrorResponse(\Exception $exception): Response;
}