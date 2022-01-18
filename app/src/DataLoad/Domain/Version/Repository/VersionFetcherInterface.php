<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Version\Repository;

use App\DataLoad\Domain\Version\ReadModel\VersionRow;
use Doctrine\DBAL\Exception as DBALException;

interface VersionFetcherInterface
{
    /**
     * @throws DBALException
     */
    public function findOldestUncoveredDeltaVersion(): ?VersionRow;

    /**
     * @throws DBALException
     */
    public function findNewestUnloadedFullVersion(): ?VersionRow;
}
