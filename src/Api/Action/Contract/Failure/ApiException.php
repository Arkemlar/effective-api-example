<?php

declare(strict_types=1);

namespace App\Api\Action\Contract\Failure;

use Exception;

class ApiException extends Exception implements ActionFailureInterface
{
}