<?php

declare(strict_types=1);

namespace App\Api\Http;

use App\Api\ApiActionInterface;
use App\Api\ApiRequestHandlerInterface;
use App\Api\Http\Exception\InvalidRequestException;
use App\Api\Http\Exception\PermissionDeniedException;
use App\Api\Http\Exception\ValidationFailedException;
use App\Api\Http\Request\RequestUnmarshallerInterface;
use App\Api\Http\Response\ResponseMarshallerInterface;
use Exception;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class HttpRequestHandler implements ApiRequestHandlerInterface
{
    private ValidatorInterface $validator;

    private RequestUnmarshallerInterface $requestUnmarshaller;

    private ResponseMarshallerInterface $responseMarshaller;

    public function __construct(
        ValidatorInterface $validator,
        RequestUnmarshallerInterface $requestUnmarshaller,
        ResponseMarshallerInterface $responseMarshaller
    ) {
        $this->validator = $validator;
        $this->requestUnmarshaller = $requestUnmarshaller;
        $this->responseMarshaller = $responseMarshaller;
    }

    public function handleRequest($request, ApiActionInterface $action, array $context = []): Response
    {
        if (!$request instanceof Request) {
            throw new InvalidArgumentException(sprintf('This handler can handle only requests of type %s', Request::class));
        }

        // extract request data
        if (!$this->requestUnmarshaller->supports($request)) {
            $this->responseMarshaller->invalidRequestResponse(new InvalidRequestException('Unable to process request'));
        }
        try {
            $data = $this->requestUnmarshaller->unmarshal($request);
        } catch (InvalidRequestException $exception) {
            $this->responseMarshaller->invalidRequestResponse($exception);
        }

        // pre-validation
        $validationResult = $this->validator->validate($data, $action->getConstraints(),
            $context[ApiRequestHandlerInterface::CONTEXT_OPTION__VALIDATION_GROUPS] ?? null);
        if ($validationResult->count() > 0) {
            $this->responseMarshaller->validationFailedResponse($validationResult);
        }

        // execute
        try {
            $output = $action->execute($data, $context);
        } catch (InvalidRequestException $exception) {  // @TODO allow exception with custom status and headers
            $this->responseMarshaller->invalidRequestResponse($exception);
        } catch (PermissionDeniedException $exception) {
            return $this->responseMarshaller->permissionDeniedResponse($exception);
        } catch (ValidationFailedException $exception) {
            $this->responseMarshaller->validationFailedResponse($exception->getConstraintViolations());
        } catch (Exception $exception) {
            return $this->responseMarshaller->internalServerErrorResponse($exception);
        }

        return $this->responseMarshaller->successResponse($output);
    }
}
