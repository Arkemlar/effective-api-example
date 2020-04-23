<?php

declare(strict_types=1);

namespace App\Api\Action\Implementation;

use App\Api\Action\Contract\Failure\ActionFailureInterface;
use App\Api\Action\Contract\FailureHandlerInterface;
use Exception;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class SimpleFailureHandler implements FailureHandlerInterface
{
    public function handleException(Exception $exception)
    {
        return ['code' => $exception->getCode(), 'message' => $exception->getMessage()];
    }

    public function handleActionFailure(ActionFailureInterface $failure)
    {
        return ['code' => $failure->getCode(), 'message' => $failure->getMessage()];
    }

    public function handleValidationFailure(ConstraintViolationListInterface $constraintViolations)
    {
        $result = [];
        /** @var ConstraintViolationInterface $violation */
        foreach ($constraintViolations as $violation) {
            $result[] = [
                'code' => $violation->getCode(),
                'message' => $violation->getMessage(),
                'value' => $violation->getInvalidValue(),
                'path' => $violation->getPropertyPath(),
            ];
        }

        return $result;
    }
}