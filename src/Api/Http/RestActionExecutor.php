<?php

declare(strict_types=1);

namespace App\Api\Http;

use App\Api\Action\Contract\ActionExecutorInterface;
use App\Api\Action\Contract\ApiActionInterface;
use App\Api\Action\Contract\Failure\ActionFailureInterface;
use App\Api\Action\Contract\FailureHandlerInterface;
use App\Api\Http\Success\ActionSucceedInterface;
use App\Api\Http\Success\SuccessHandlerInterface;
use Exception;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class RestActionExecutor implements ActionExecutorInterface
{
    private ValidatorInterface $validator;
    private FailureHandlerInterface $failureHandler;
    private SuccessHandlerInterface $successHandler;

    public function __construct(
        ValidatorInterface $validator,
        FailureHandlerInterface $failureHandler,
        Success\SuccessHandlerInterface $successHandler
    ) {
        $this->validator = $validator;
        $this->failureHandler = $failureHandler;
        $this->successHandler = $successHandler;
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
            if ($responseData instanceof ActionSucceedInterface) {
                return $this->successHandler->handleSuccess($responseData);
            }
            if ($responseData instanceof ActionFailureInterface) {
                return $this->failureHandler->handleActionFailure($responseData);
            }
            return $this->successHandler->handleData($responseData);
        } catch (Exception $exception) {
            return $this->failureHandler->handleException($exception);
        }
    }
}