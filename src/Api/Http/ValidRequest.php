<?php

declare(strict_types=1);

namespace App\Api\Http;

use App\Api\Http\Contract\ValidRequestInterface;
use Symfony\Component\HttpFoundation\Request;

final class ValidRequest implements ValidRequestInterface
{
    private Request $request;

    /** @var mixed */
    private $validData;

    /**
     * @param mixed $validData
     */
    public function __construct(Request $request, $validData)
    {
        $this->request = $request;
        $this->validData = $validData;
    }

    public function getDataset()
    {
        return $this->validData;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}