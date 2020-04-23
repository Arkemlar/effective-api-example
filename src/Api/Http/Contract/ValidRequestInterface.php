<?php

declare(strict_types=1);

namespace App\Api\Http\Contract;

use Symfony\Component\HttpFoundation\Request;

interface ValidRequestInterface
{
    /**
     * Retrieve validated dataset
     *
     * @return mixed
     */
    public function getDataset();
    public function getRequest(): Request;
}