<?php

declare(strict_types=1);

namespace App\Api\Http\Dataset;

use App\Api\Http\Contract\InputDatasetInterface;
use LogicException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class OrdinaryDataset implements InputDatasetInterface
{
    /** @var mixed */
    private $data;

    /** @var Constraint|Constraint[] */
    private $constraints;

    /** @var string|string[]|null */
    private $groups;

    private bool $isValid = false;

    /**
     * @param mixed $data
     * @param Constraint|Constraint[] $constraints
     * @param string|string[]|null $groups
     */
    public function __construct($data, $constraints, $groups)
    {
        $this->data = $data;
        $this->constraints = $constraints;
        $this->groups = $groups;
    }

    public function validate(ValidatorInterface $validator): ?ConstraintViolationListInterface
    {
        $validationResult = $validator->validate(
            $this->data,
            $this->constraints,
            $this->groups
        );
        if ($validationResult->count() !== 0) {
            return $validationResult;
        }
        $this->isValid = true;

        return null;
    }

    public function getValidData(): array
    {
        if (!$this->isValid) {
            throw new LogicException('The data is not valid, use isValid() first');
        }

        return $this->data;
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }
}