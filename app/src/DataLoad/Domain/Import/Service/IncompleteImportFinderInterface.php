<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Import\Service;

use App\DataLoad\Domain\Import\Entity\Import;

interface IncompleteImportFinderInterface
{
    public const EXPIRE_INTERVAL = 3600 * 24;

    /**
     * @return list<Import>
     */
    public function find(): array;

    public function check(): bool;
}
