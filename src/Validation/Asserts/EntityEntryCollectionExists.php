<?php

declare(strict_types=1);

namespace App\Validation\Asserts;

use App\Validator\BaseEntityEntryConstraint;

final class EntityEntryCollectionExists extends BaseEntityEntryConstraint
{
    public const ONE_OR_MORE_ENTRIES_NOT_FOUND_ERROR = '02ca633a-4d7a-11ea-b77f-2e728ce88125';

    public string $messageByDefault = 'One or more entries not exists';
    public string $messageWithMissingIds = 'Following entries not exists: {{ ids }}';

    /** true - allows to see the list of missing ids in error message, but makes SQL query more heavy */
    public bool $showMissingIds = false;

    /** @var array */
    protected static $errorNames = [
        self::ONE_OR_MORE_ENTRIES_NOT_FOUND_ERROR => 'ONE_OR_MORE_ENTRY_NOT_FOUND_ERROR',
    ];
}
