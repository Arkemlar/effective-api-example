<?php

namespace App\Api\Http\Exception;

use DomainException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationFailedException extends DomainException
{
    public const ERROR_CODE = 1;
    public const ERROR_MSG = 'Validation failed';

    private ConstraintViolationListInterface $constraintViolations;

    public function __construct(ConstraintViolationListInterface $constraintViolations)
    {
        parent::__construct(self::ERROR_MSG, self::ERROR_CODE);
        $this->constraintViolations = $constraintViolations;
    }

    public function getConstraintViolations(): ConstraintViolationListInterface
    {
        return $this->constraintViolations;
    }
}