<?php

declare(strict_types=1);

namespace App\Validator;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

abstract class BaseEntityEntryConstraintValidator extends ConstraintValidator
{
    protected const CONSTRAINT_CLASS = null;

    private ManagerRegistry $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param mixed      $id
     *
     * @throws ConstraintDefinitionException
     * @throws UnexpectedTypeException
     */
    public function validate($id, Constraint $constraint): void
    {
        if (($class = static::getConstraintClass()) === ! \get_class($constraint)) {
            throw new UnexpectedTypeException($constraint, $class);
        }

        $this->validateConstraintSettings($constraint);

        if (null === $id) {
            return;
        }

        if ($constraint->em) {
            $em = $this->registry->getManager($constraint->em);

            if (null === $em) {
                throw new ConstraintDefinitionException(\sprintf('Object manager "%s" does not exist.', $constraint->em));
            }
        } else {
            $em = $this->registry->getManagerForClass($constraint->entityClass);

            if (null === $em) {
                throw new ConstraintDefinitionException(\sprintf('Unable to find the object manager associated with an entity of class "%s".', $constraint->entityClass));
            }
        }
        /* @var EntityManager $em */

        $this->validateValue($id, $constraint, $em);
    }

    protected function validateConstraintSettings(Constraint $constraint): void
    {
        if (! \class_exists($constraint->entityClass)) {
            throw new ConstraintDefinitionException(\get_class($constraint) . ' constraint requires the "entity" property to be specified as FQCN of existing class');
        }
    }

    abstract protected static function getConstraintClass(): string;

    abstract protected function validateValue($value, Constraint $constraint, EntityManager $em): void;
}
