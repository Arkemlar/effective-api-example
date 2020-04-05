<?php

declare(strict_types=1);

namespace App\Validator;

final class EntityEntryExists extends BaseEntityEntryConstraint
{
    public const ENTRY_NOT_FOUND_ERROR = '8e179f1b-97aa-4560-a02f-2a8b42e49df7';

    /** @var string */
    public $message = 'Entry with id = "{{ id }}" not exists';

    /** @var array */
    protected static $errorNames = [
        self::ENTRY_NOT_FOUND_ERROR => 'ENTRY_NOT_FOUND_ERROR',
    ];
}
