<?php

declare(strict_types=1);

namespace App\Validation\Validators;

use App\Validation\Asserts\ValidateSequentially;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class ValidateSequentiallyValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (! $constraint instanceof ValidateSequentially) {
            throw new UnexpectedTypeException($constraint, ValidateSequentially::class);
        }

        $context = $this->context;

        $validator = $context->getValidator()->inContext($context);

        $violationCount = $context->getViolations()->count();
        foreach ($constraint->constraintGroups as $constraintGroup) {
            $validator->validate($value, $constraintGroup);
            if ($context->getViolations()->count() > $violationCount) {
                break;
            }
        }
    }
}
