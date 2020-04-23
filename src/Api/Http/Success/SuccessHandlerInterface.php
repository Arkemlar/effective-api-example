<?php

declare(strict_types=1);

namespace App\Api\Http\Success;

interface SuccessHandlerInterface
{
    public function handleSuccess(ActionSucceedInterface $result);
    public function handleData($resultData);
}