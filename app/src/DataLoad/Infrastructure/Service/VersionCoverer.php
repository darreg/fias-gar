<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Domain\Version\ReadModel\VersionRow;
use App\DataLoad\Domain\Version\Repository\VersionFetcherInterface;
use App\DataLoad\Domain\Version\Service\VersionCovererInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DBALException;
use RuntimeException;

class VersionCoverer implements VersionCovererInterface
{
    private VersionFetcherInterface $versionFetcher;
    private Connection $connection;

    public function __construct(
        Connection $connection,
        VersionFetcherInterface $versionFetcher
    ) {
        $this->versionFetcher = $versionFetcher;
        $this->connection = $connection;
    }

    /**
     * @throws RuntimeException
     */
    public function cover(string $versionId): void
    {
        try {
            $previousVersionRows = $this->versionFetcher->findPrevious($versionId);
        } catch (DBALException $e) {
            throw new RuntimeException("Unable to get previous versions for '{$versionId}'", 0, $e);
        }

        $versionIds = array_map(static fn (VersionRow $versionRow) => $versionRow->id, $previousVersionRows);
        try {
            /** @psalm-suppress InvalidArgument */
            $this->connection->executeStatement(
                'UPDATE version SET covered=true WHERE id in (?)',
                [$versionIds],
                [Connection::PARAM_INT_ARRAY]
            );
        } catch (DBALException $e) {
            throw new RuntimeException('Unable to set covered field', 0, $e);
        }
    }
}
