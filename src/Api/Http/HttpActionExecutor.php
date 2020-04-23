<?php

declare(strict_types=1);

namespace App\Api\Http;

use App\Api\Action\Contract\Failure\ActionFailureInterface;
use App\Api\Action\Contract\FailureHandlerInterface;
use App\Api\Http\Contract\HttpActionExecutorInterface;
use App\Api\Http\Contract\HttpApiActionInterface;
use App\Api\Http\Success\ActionSucceedInterface;
use App\Api\Http\Success\SuccessHandlerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class HttpActionExecutor implements HttpActionExecutorInterface
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

    public function processRequest(Request $request, HttpApiActionInterface $action)
    {
        $datasetForValidation = $action->getDataset($request);
        // pre-validation
        $violations = $datasetForValidation->validate($this->validator);
        if ($violations !== null) {
            return $this->failureHandler->handleValidationFailure($violations);
        }
        $validRequest = new ValidRequest($request, $datasetForValidation->getValidData());


        // execute
        try {
            $responseData = $action->execute($validRequest);
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