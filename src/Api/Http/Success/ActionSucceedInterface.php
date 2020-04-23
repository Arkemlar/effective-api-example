<?php

declare(strict_types=1);

namespace App\Api\Http\Success;

interface ActionSucceedInterface
{
    public function getData(): ?array;

    public function getStatusCode(): int;

    public function getHeaders(): array;
}