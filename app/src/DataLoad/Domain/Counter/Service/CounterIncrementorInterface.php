<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Counter\Service;

use App\DataLoad\Domain\Counter\Entity\Counter;

interface CounterIncrementorInterface
{
    /**
     * @param Counter::COUNTER_FIELD_* $fieldName
     */
    public function inc(string $type, string $versionId, string $fieldName): void;
}
