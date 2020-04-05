<?php

namespace App\Api\Http\Request;

use Symfony\Component\HttpFoundation\Request;

class QueryRequestUnmarshaller implements RequestUnmarshallerInterface
{
    public function unmarshal(Request $request): array
    {
        return $request->query->all();
    }

    public function supports(Request $request): bool
    {
        return $request->isMethod('GET');
    }
}