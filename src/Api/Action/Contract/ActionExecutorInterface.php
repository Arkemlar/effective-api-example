<?php

declare(strict_types=1);

namespace App\Api\Action\Contract;

interface ActionExecutorInterface
{
    /**
     * @param array|object $request Request that is acquired from transport or passed by previous (decorated) handler
     * @param ApiActionInterface $action The object where your custom application logic takes in action
     * @return array|object Response that is ready for transmission by transport or processing by previous handler
     */
    public function processRequest($request, ApiActionInterface $action);
}