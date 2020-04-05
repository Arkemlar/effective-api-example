<?php

declare(strict_types=1);

namespace App\Api;

use Symfony\Component\Validator\Constraint;

interface ApiActionInterface
{
    public function getConstraints(): ?Constraint;

    public function execute(array $data, array $context = []): ?array;
}
