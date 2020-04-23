<?php

declare(strict_types=1);

namespace App\Api\Http\Failure;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationFailure extends ActionFailure
{
    public const HTTP_STATUS_CODE = Response::HTTP_BAD_REQUEST;
    public const CODE = 'a301bee6-501c-4042-824f-d9ccbd13d51f';
    public const MESSAGE = 'Request data validation failed';

    protected ConstraintViolationListInterface $constraintViolations;

    public function __construct(ConstraintViolationListInterface $constraintViolations)
    {
        parent::__construct(self::CODE, self::MESSAGE);
        $this->constraintViolations = $constraintViolations;
    }

    public function getConstraintViolations(): ConstraintViolationListInterface
    {
        return $this->constraintViolations;
    }

    public static function new(ConstraintViolationListInterface $constraintViolations): self
    {
        return new self($constraintViolations);
    }
}