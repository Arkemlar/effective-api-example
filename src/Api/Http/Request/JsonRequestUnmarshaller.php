<?php

namespace App\Api\Http\Request;

use App\Api\Http\Exception\InvalidRequestException;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;

class JsonRequestUnmarshaller implements RequestUnmarshallerInterface
{
    private DecoderInterface $jsonDecoder;

    public function __construct(DecoderInterface $jsonDecoder)
    {
        if (!$jsonDecoder->supportsDecoding('json')) {
            throw new InvalidArgumentException('That decoder cannot decode from json');
        }
        $this->jsonDecoder = $jsonDecoder;
    }

    /**
     * @throws InvalidRequestException
     */
    public function unmarshal(Request $request): array
    {
        return array_merge(
            $request->query->all(),
            $this->decode($request->getContent())
        );
    }

    public function supports(Request $request): bool
    {
        return 'application/json' === $request->getContentType();
    }

    /**
     * @throws InvalidRequestException
     */
    private function decode(string $data): array
    {
        try {
            $data = $this->jsonDecoder->decode($data, 'json', ['json_decode_associative' => true]);
        } catch (UnexpectedValueException $exception) {
            throw new InvalidRequestException('Unable to decode json request', null, $exception);
        }

        return $data;
    }
}