<?php

declare(strict_types=1);

namespace App\Api\Action\Contract\Failure;

interface ActionFailureInterface
{
    public function getCode(); //scalar or object (like Ramsey\Uuid);
    public function getMessage(): string;
}