<?php

declare(strict_types=1);

namespace App\Api\Http\Success;

use Symfony\Component\HttpFoundation\Response;

class Success implements ActionSucceedInterface
{
    private ?array $data;
    private int $statusCode;
    private array $headers = [];

    public function __construct(?array $data, int $statusCode = Response::HTTP_OK)
    {
        $this->data = $data;
        $this->statusCode = $statusCode;
    }

    public function addHeader(string $name, string $value): void
    {
        $this->headers[$name] = $value;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }


}