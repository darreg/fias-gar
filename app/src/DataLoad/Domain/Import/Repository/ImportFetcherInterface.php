<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Import\Repository;

use App\DataLoad\Domain\Import\ReadModel\ImportRow;
use DateTimeImmutable;

interface ImportFetcherInterface
{
    public const EXPIRE_INTERVAL = 3600 * 24;

    /**
     * @return list<ImportRow>
     */
    public function findAll(): array;

    /**
     * @return list<ImportRow>
     */
    public function findUncompleted(): array;

    /**
     * @return list<ImportRow>
     */
    public function findCompleted(): array;

    /**
     * @return list<ImportRow>
     */
    public function findCompletedOlderThan(DateTimeImmutable $dateTime): array;

    public function isUncompletedExists(): bool;
}
