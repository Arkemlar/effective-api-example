<?php

declare(strict_types=1);

namespace App\Api\Examples\Rest;

use App\Api\Http\Success\ActionSucceedInterface;
use App\Api\Http\Success\SuccessHandlerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class SuccessHandler implements SuccessHandlerInterface
{
    private JsonResponseFormatter $responseFormatter;

    public function __construct(JsonResponseFormatter $responseFormatter)
    {
        $this->responseFormatter = $responseFormatter;
    }

    public function handleSuccess(ActionSucceedInterface $result): JsonResponse
    {
        $responseData = $this->responseFormatter->formatSuccessResponse($result->getData());

        return new JsonResponse($responseData, $result->getStatusCode(), $result->getHeaders());
    }

    public function handleData($resultData): JsonResponse
    {
        return new JsonResponse($resultData, Response::HTTP_OK);
    }
}