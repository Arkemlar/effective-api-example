<?php

declare(strict_types=1);

namespace App\Api\Http\Dataset\ValidationSequence;

use Symfony\Component\Validator\Constraint;

final class ValidationSequenceItem
{
    public string $name;

    /** @var mixed */
    public $dataset;

    /** @var Constraint[] */
    public array $constraints;

    /** @var string|string[]|null {@see ValidatorInterface::validate()} to know more about groups */
    public $groups;

    public bool $isValid = false;

    /**
     * @param mixed $dataset
     */
    public function __construct($dataset, string $name)
    {
        $this->dataset = $dataset;
        $this->name = $name;
    }

    public function isItemNotFinished(): bool
    {
        return count($this->constraints) === 0;
    }
}