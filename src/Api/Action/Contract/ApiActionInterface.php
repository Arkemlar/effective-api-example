<?php

declare(strict_types=1);

namespace App\Api\Action\Contract;

use App\Api\Action\Contract\Failure\ApiException;
use Exception;
use Symfony\Component\Validator\Constraint;

/**
 * This interface is meant to be used in every action/method in your API. Doesn't matter REST or RPC, or whatever.
 * The are two key concepts about action:
 * - constraints that you define to validate input data
 * - execute method that is your entry point when application logic comes in action
 * The execute() method signature is not strong because it generally might be array or objects (like DTO) so
 * it would be better if you extend this interface and specify concrete types for it`s input and output.
 *
 * @package App\Api\Action\Contract
 */
interface ApiActionInterface
{
    public function getConstraints(): Constraint;

    /**
     * The action (in terms of REST) or method/procedure (as in RPC).
     *
     * @param mixed $data Action data  or method parameters
     *
     * @throws ApiException
     * @throws Exception
     *
     * @return mixed Action execution/method call result
     */
    public function execute($data);
}
