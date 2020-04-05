<?php

namespace App\Api\Http\Request;

use App\Api\Http\Exception\InvalidRequestException;
use Symfony\Component\HttpFoundation\Request;

interface RequestUnmarshallerInterface
{
    /**
     * @throws InvalidRequestException
     */
    public function unmarshal(Request $request): array;

    public function supports(Request $request): bool;
}