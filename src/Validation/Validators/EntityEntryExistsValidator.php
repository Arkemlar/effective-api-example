<?php

declare(strict_types=1);

namespace App\Validator;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\TransactionRequiredException;
use Symfony\Component\Validator\Constraint;

final class EntityEntryExistsValidator extends BaseEntityEntryConstraintValidator
{
    protected static function getConstraintClass(): string
    {
        return EntityEntryExists::class;
    }

    public function buildViolation(string $message, $value): void
    {
        $this->context->buildViolation($message)
            ->setParameter('{{ id }}', $this->formatValue($value))
            ->setCode(EntityEntryExists::ENTRY_NOT_FOUND_ERROR)
            ->setInvalidValue($value)
            ->addViolation();
    }

    /**
     * @param EntityEntryExists $constraint
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws TransactionRequiredException
     */
    protected function validateValue($value, Constraint $constraint, EntityManager $em): void
    {
        if ('id' === $constraint->field) {
            $result = $em->find($constraint->entityClass, $value);
        } else {
            $result = $em->getRepository($constraint->entityClass)->findOneBy([$constraint->field => $value]);
        }
        if (null === $result) {
            $this->buildViolation($constraint->message, $value);
        }
    }
}
