<?php

declare(strict_types=1);

namespace App\Api\Http\Dataset\ValidationSequence;

use App\Api\Http\Contract\InputDatasetInterface;
use LogicException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ValidationSequence implements InputDatasetInterface
{
    /** @var ValidationSequenceItem[] */
    private array $sequence;

    private bool $isValid = false;

    public function __construct(array $sequence)
    {
        $this->sequence = $sequence;
    }

    public function validate(ValidatorInterface $validator): ?ConstraintViolationListInterface
    {
        foreach ($this->sequence as $item) {
            $validationResult = $validator->validate(
                $item->dataset,
                $item->constraints,
                $item->groups
            );
            if ($validationResult->count() !== 0) {
                return $validationResult;
            }
            $item->isValid = true;
        }
        $this->isValid = true;

        return null;
    }

    public function getValidData(): array
    {
        if (!$this->isValid) {
            throw new LogicException('The data is not valid, use isValid() first');
        }

        $datasets = [];
        foreach ($this->sequence as $item) {
            $datasets[$item->name] = $item->dataset;
        }

        return $datasets;
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }
}