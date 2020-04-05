<?php

declare(strict_types=1);

namespace App\Validator;

final class EntityEntryNotExists extends BaseEntityEntryConstraint
{
    public const ENTRY_ALREADY_EXISTS_ERROR = '70b492a0-4045-11e9-b210-d663bd873d93';

    /** @var string */
    public $message = 'Entry with id = "{{ id }}" already exists';

    /** @var array */
    protected static $errorNames = [
        self::ENTRY_ALREADY_EXISTS_ERROR => 'ENTRY_ALREADY_EXISTS_ERROR',
    ];
}
