<?php

declare(strict_types=1);

namespace App\Api\Http\Dataset\ValidationSequence;

use LogicException;
use Symfony\Component\Validator\Constraint;

final class ValidationSequenceBuilder
{
    private array $sequence;

    private ValidationSequenceItem $currentItem;

    public static function new(): self
    {
        return new self();
    }

    public function forDataset($data, string $name): self
    {
        if (isset($this->currentItem)) {
            $this->finishDataset();
        }

        $this->currentItem = new ValidationSequenceItem($data, $name);
        
        return $this;
    }

    public function addConstraint(Constraint $constraint): self
    {
        if (!isset($this->currentItem)) {
            throw new LogicException('First define dataset, then add constraints and set groups');
        }
        $this->currentItem->constraints[] = $constraint;

        return $this;
    }

    public function setGroups($groups): self 
    {
        if (!isset($this->currentItem)) {
            throw new LogicException('First define dataset, then add constraints and set groups');
        }
        $this->currentItem->groups = $groups;

        return $this;
    }

    public function getSequence(): ValidationSequence
    {
        $this->finishDataset();

        return new ValidationSequence($this->sequence);
    }

    private function finishDataset(): void
    {
        if ($this->currentItem->isItemNotFinished()) {
            throw new LogicException('Each dataset must have at least one constraint');
        }
        $this->sequence[$this->currentItem->name] = $this->currentItem;
        unset($this->currentItem);
    }
}