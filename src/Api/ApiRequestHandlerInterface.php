<?php

namespace App\Api;

interface ApiRequestHandlerInterface
{
    public const CONTEXT_OPTION__VALIDATION_GROUPS = 'validation_groups';

    /**
     * @param $request object Request that is acquired from transport
     * @param ApiActionInterface $action The object where your custom application logic takes in action
     * @param array $context Arbitrary context parameters that might be used by handler or {@see ApiActionInterface} methods
     * @return object Response that is ready for transmission by transport
     */
    public function handleRequest($request, ApiActionInterface $action, array $context = []);
}