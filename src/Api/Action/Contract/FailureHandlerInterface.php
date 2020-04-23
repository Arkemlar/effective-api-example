<?php

declare(strict_types=1);

namespace App\Api\Action\Contract;

use App\Api\Action\Contract\Failure\ActionFailureInterface;
use Exception;
use Symfony\Component\Validator\ConstraintViolationListInterface;

interface FailureHandlerInterface
{
    public function handleValidationFailure(ConstraintViolationListInterface $constraintViolations);
    public function handleActionFailure(ActionFailureInterface $failure);
    public function handleException(Exception $exception);
}