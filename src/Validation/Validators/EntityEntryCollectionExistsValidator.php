<?php

declare(strict_types=1);

namespace App\Validation\Validators;

use App\Validation\Asserts\EntityEntryCollectionExists;
use App\Validator\BaseEntityEntryConstraintValidator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class EntityEntryCollectionExistsValidator extends BaseEntityEntryConstraintValidator
{
    protected static function getConstraintClass(): string
    {
        return EntityEntryCollectionExists::class;
    }

    public function buildViolation(string $message, $value): void
    {
        $this->context->buildViolation($message)
            ->setParameter('{{ ids }}', $this->formatValue($value))
            ->setCode(EntityEntryCollectionExists::ONE_OR_MORE_ENTRIES_NOT_FOUND_ERROR)
            ->setInvalidValue($value)
            ->addViolation();
    }

    /**
     * @param EntityEntryCollectionExists $constraint
     * @param mixed                       $value
     */
    protected function validateValue($value, Constraint $constraint, EntityManager $em): void
    {
        if (! \is_array($value) && ! $value instanceof \Traversable) {
            throw new UnexpectedValueException($value, 'iterable');
        }
        foreach ($value as $item) {
            if (\is_int($item) || \is_string($item)) {
                continue;
            }

            throw new UnexpectedValueException($value, 'int or string');
        }

        if (true === $constraint->showMissingIds) {
            $result = $em->getRepository($constraint->entityClass)->findBy([$constraint->field => $value]);
            if (\count($value) !== \count($result)) {

                $this->buildViolation($constraint->messageWithMissingIds, \array_values(\array_diff($value, $result)));
            }
        } else {
            $result = $em->getRepository($constraint->entityClass)->count([$constraint->field => $value]);
            if (\count($value) !== $result) {

                $this->buildViolation($constraint->messageByDefault, $value);
            }
        }
    }
}
