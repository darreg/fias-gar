<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Version\Repository;

use App\DataLoad\Domain\Version\ReadModel\VersionRow;

interface VersionFetcherInterface
{
    /**
     * @return array<int, VersionRow>
     */
    public function findAll(): array;

    public function findOldestUncoveredDeltaVersion(): ?VersionRow;

    public function findNewestUnloadedFullVersion(): ?VersionRow;

    /**
     * @return list<VersionRow>
     */
    public function findPrevious(string $id): array;
}
