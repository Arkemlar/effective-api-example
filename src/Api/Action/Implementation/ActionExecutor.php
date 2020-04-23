<?php

declare(strict_types=1);

namespace App\Api\Action\Implementation;

use App\Api\Action\Contract\ActionExecutorInterface;
use App\Api\Action\Contract\ApiActionInterface;
use App\Api\Action\Contract\Failure\ActionFailureInterface;
use App\Api\Action\Contract\FailureHandlerInterface;
use Exception;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ActionExecutor implements ActionExecutorInterface
{
    private ValidatorInterface $validator;
    private FailureHandlerInterface $failureHandler;

    public function __construct(ValidatorInterface $validator, FailureHandlerInterface $failureHandler)
    {
        $this->validator = $validator;
        $this->failureHandler = $failureHandler;
    }

    public function processRequest($requestData, ApiActionInterface $action)
    {
        $constraints = $action->getConstraints();
        // pre-validation
        $validationResult = $this->validator->validate(
            $requestData,
            $constraints,
        );
        if ($validationResult->count() > 0) {
            return $this->failureHandler->handleValidationFailure($validationResult);
        }

        // execute
        try {
            $responseData = $action->execute($requestData);
            if ($responseData instanceof ActionFailureInterface) {
                return $this->failureHandler->handleActionFailure($responseData);
            }
        } catch (Exception $exception) {
            return $this->failureHandler->handleException($exception);
        }

        return $responseData;
    }
}