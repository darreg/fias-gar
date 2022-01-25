<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Import\Service;

use App\DataLoad\Domain\Import\Entity\Import;

interface ImportCounterIncrementorInterface
{
    /**
     * @param Import::COUNTER_FIELD_* $fieldName
     */
    public function inc(string $type, string $versionId, string $fieldName): void;
}
