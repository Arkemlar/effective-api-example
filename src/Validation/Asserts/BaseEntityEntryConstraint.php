<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

abstract class BaseEntityEntryConstraint extends Constraint
{
    /** @var string */
    public $entityClass;

    /** @var string|null */
    public $em;

    /** @var string Field name to be mapped to validated value */
    public $field = 'id';

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    /**
     * The validator must be defined as a service with this name.
     *
     * @return string
     */
    public function validatedBy()
    {
        return str_replace('Asserts', 'Validators', static::class . 'Validator');
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOption()
    {
        return 'entityClass';
    }

    /**
     * {@inheritdoc}
     */
    public function getRequiredOptions()
    {
        return ['entityClass'];
    }
}
