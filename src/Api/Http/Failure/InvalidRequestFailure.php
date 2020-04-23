<?php

declare(strict_types=1);

namespace App\Api\Http\Failure;

use Symfony\Component\HttpFoundation\Response;

class InvalidRequestFailure extends ActionFailure
{
    public const HTTP_STATUS_CODE = Response::HTTP_BAD_REQUEST;

    protected ?array $data;

    public function __construct(string $code, string $message, array $data = null)
    {
        parent::__construct($code, $message);
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public static function new(string $code, string $message, array $data = null): self
    {
        return new self($code, $message, $data);
    }
}