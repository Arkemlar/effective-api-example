<?php

declare(strict_types=1);

namespace App\Api\Http\Contract;

use Symfony\Component\HttpFoundation\Request;

interface HttpActionExecutorInterface
{
    public function processRequest(Request $request, HttpApiActionInterface $action);
}