<?php

declare(strict_types=1);

namespace App\Api\Http\Failure;

use App\Api\Action\Contract\Failure\ActionFailureInterface;

abstract class ActionFailure implements ActionFailureInterface
{
    protected string $code;
    protected string $message;

    public function __construct(string $code, string $message)
    {
        $this->code = $code;
        $this->message = $message;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}