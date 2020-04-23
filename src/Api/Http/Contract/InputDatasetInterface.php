<?php

declare(strict_types=1);

namespace App\Api\Http\Contract;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

interface InputDatasetInterface
{
    public function validate(ValidatorInterface $validator): ?ConstraintViolationListInterface;
    public function getValidData();
    public function isValid(): bool;
}