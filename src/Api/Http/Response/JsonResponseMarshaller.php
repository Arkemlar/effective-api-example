<?php

namespace App\Api\Http\Response;

use App\Api\Http\Exception\InvalidRequestException;
use App\Api\Http\Exception\PermissionDeniedException;
use App\Api\Http\Exception\ValidationFailedException;
use Exception;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class JsonResponseMarshaller implements ResponseMarshallerInterface, LoggerAwareInterface
{
    /** Encode <, >, ', &, and " characters in the JSON, making it also safe to be embedded into HTML.
     * JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT (15 total == these are defaults) +
     * JSON_UNESCAPED_UNICODE (256) = 271
     */
    public const ENCODING_OPTIONS = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT |
        JSON_UNESCAPED_UNICODE;

    use LoggerAwareTrait;

    private bool $debugFlag;
    private EncoderInterface $jsonEncoder;
    private NormalizerInterface $normalizer;

    public function __construct(bool $debugFlag, EncoderInterface $jsonEncoder, NormalizerInterface $normalizer)
    {
        if (!$jsonEncoder->supportsEncoding('json')) {
            throw new \InvalidArgumentException('That encoder cannot encode to json');
        }
        $this->jsonEncoder = $jsonEncoder;
        $this->debugFlag = $debugFlag;
        $this->normalizer = $normalizer;
    }

    public function successResponse(?array $data = null, int $status = Response::HTTP_OK, array $headers = []): Response
    {
        if ($data === null) {
            return Response::create(null, $status, $headers);
        }

        $data = $this->prepareSuccessResponseBody($data);

        return $this->makeJsonResponse($data, $status, $headers);
    }

    public function validationFailedResponse(ConstraintViolationListInterface $constraintViolationList): Response
    {
        try {
            $normalizedConstraintViolations = $this->normalizer->normalize($constraintViolationList);
        } catch (ExceptionInterface $exception) {
            $this->logError($exception);

            return $this->internalServerErrorResponse($exception);
        }

        $data = $this->prepareErrorResponseBody(
            ValidationFailedException::ERROR_CODE,
            ValidationFailedException::ERROR_MSG,
            $normalizedConstraintViolations,
        );

        return $this->makeJsonResponse($data, Response::HTTP_BAD_REQUEST);
    }

    public function permissionDeniedResponse(PermissionDeniedException $exception): Response
    {
        $data = $this->prepareErrorResponseBody(
            $exception->getCode(),
            $exception->getMessage(),
        );

        return $this->makeJsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    public function invalidRequestResponse(InvalidRequestException $exception): Response
    {
        $data = $this->prepareErrorResponseBody(
            $exception->getCode(),
            $exception->getMessage(),
        );

        return $this->makeJsonResponse($data, Response::HTTP_BAD_REQUEST);
    }

    public function internalServerErrorResponse(Exception $exception): Response
    {
        if (!$this->debugFlag) {
            return Response::create(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $data = $this->prepareErrorResponseBody(
            $exception->getCode(),
            $exception->getMessage(),
            [
                'trace' => $exception->getTraceAsString(),
                'previous' => $exception->getPrevious()
                    ? [
                        'code' => $exception->getPrevious(),
                        'message' => $exception->getMessage(),
                        'trace' => $exception->getTraceAsString(),
                    ]
                    : null,
            ]
        );

        return $this->makeJsonResponse($data, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    private function prepareSuccessResponseBody(?array $result): array
    {
        return [
            'result' => $result,
            'error' => null
        ];
    }

    /**
     * @param int|string $code
     */
    private function prepareErrorResponseBody($code, string $message, array $data = null): array
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

    private function makeJsonResponse(array $data, int $status = Response::HTTP_OK, array $headers = []): Response
    {
        try {
            $string = $this->jsonEncoder->encode($data, 'json', ['json_encode_options' => self::ENCODING_OPTIONS]);
        } catch (Exception $exception) {
            $this->logError($exception);
            if (!$this->debugFlag) {
                return Response::create(null, Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $string = sprintf('{"data": null, "error": {"code": %d, "message": "%s", "data": null} }',
                $exception->getCode(),
                $exception->getMessage()
            );
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return JsonResponse::fromJsonString($string, $status, $headers);
    }

    private function logError(Exception $exception): void
    {
        if($this->logger instanceof LoggerInterface) {
            $this->logger->error(
                'API response could not be sent by error: '.$exception->getMessage(),
                ['exception' => $exception]
            );
        }
    }

}