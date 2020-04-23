<?php

declare(strict_types=1);

namespace App\Api\Http\Success;

use Symfony\Component\HttpFoundation\Response;

class Redirect implements ActionSucceedInterface
{
    private int $statusCode;
    private array $headers = [];

    public function __construct(int $statusCode, $location)
    {
        $this->statusCode = $statusCode;
        $this->addHeader('Location', $location);
    }

    public function addHeader(string $name, string $value): void
    {
        $this->headers[$name] = $value;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Used for GET methods
     */
    public static function MovedPermanently(string $location): self
    {
        return new self(Response::HTTP_MOVED_PERMANENTLY, $location);
    }

    /**
     * Used for non-GET methods
     */
    public static function PermanentRedirect(string $location): self
    {
        return new self(Response::HTTP_PERMANENTLY_REDIRECT, $location);
    }

    /**
     * Used after resource modification (POST, PUT, PATCH) to prevent multiple execution of same request
     */
    public static function SeeOther(string $location): self
    {
        return new self(Response::HTTP_SEE_OTHER, $location);
    }

    /**
     * Used to tell client that the cache is still valid and it can use it
     */
    public static function NotModified(string $location): self
    {
        return new self(Response::HTTP_NOT_MODIFIED, $location);
    }

    public function getData(): ?array
    {
        return null;
    }
}