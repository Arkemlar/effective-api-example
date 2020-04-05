<?php

declare(strict_types=1);

namespace App\Validator;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Symfony\Component\Validator\Constraint;

final class EntityEntryNotExistsValidator extends BaseEntityEntryConstraintValidator
{
    protected static function getConstraintClass(): string
    {
        return EntityEntryNotExists::class;
    }

    public function buildViolation(string $message, $value): void
    {
        $this->context->buildViolation($message)
            ->setParameter('{{ id }}', $this->formatValue($value))
            ->setCode(EntityEntryNotExists::ENTRY_ALREADY_EXISTS_ERROR)
            ->setInvalidValue($value)
            ->addViolation();
    }

    /**
     * @param EntityEntryNotExists $constraint
     * @throws ORMException
     */
    protected function validateValue($value, Constraint $constraint, EntityManager $em): void
    {
        if ('id' === $constraint->field) {
            $result = $em->find($constraint->entityClass, $value);
        } else {
            $result = $em->getRepository($constraint->entityClass)->findOneBy([$constraint->field => $value]);
        }
        if (null !== $result) {
            $this->buildViolation($constraint->message, $value);
        }
    }
}
