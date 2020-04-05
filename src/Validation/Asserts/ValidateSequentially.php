<?php

declare(strict_types=1);

namespace App\Validation\Asserts;

use Symfony\Component\Validator\Constraint;

/**
 * Splits the specified list of constraints by groups and calls validation on groups sequentially.
 * If at some point some of constraints in group raises violation, then remaining constraints in group will be validated
 * but next groups won't be validated.
 *
 * Options set:
 * - constraintGroups - groups of constraints. Each group might be represented by a single constraint or array of constraints.
 */
final class ValidateSequentially extends Constraint
{
    /** @var array[] An array of constraints separated to groups (array of constraints or array of constraints) */
    public $constraintGroups = [];

    public function getDefaultOption()
    {
        return 'constraintGroups';
    }

    public function getRequiredOptions()
    {
        return ['constraintGroups'];
    }

    public function validatedBy()
    {
        return str_replace('asserts', 'validators', static::class . 'Validator1');
    }
}
