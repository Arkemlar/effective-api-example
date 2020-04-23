<?php

declare(strict_types=1);

namespace App\Api\Http\Contract;

use Symfony\Component\HttpFoundation\Request;

interface HttpApiActionInterface
{
    public function getDataset(Request $request): InputDatasetInterface;

    public function execute(ValidRequestInterface $request);
}