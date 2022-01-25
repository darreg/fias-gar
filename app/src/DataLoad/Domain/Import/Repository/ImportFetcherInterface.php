<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Import\Repository;

use App\DataLoad\Domain\Import\Entity\Import;
use App\DataLoad\Domain\Import\ReadModel\ImportRow;

interface ImportFetcherInterface
{
    public const EXPIRE_INTERVAL = 3600 * 24;

    /**
     * @return array<int, Import>
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

    public function isIncompleteExists(): bool;
}
