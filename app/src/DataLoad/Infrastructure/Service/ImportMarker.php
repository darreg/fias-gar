<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Domain\Import\ReadModel\ImportRow;
use App\DataLoad\Domain\Import\Service\ImportMarkerInterface;
use App\DataLoad\Infrastructure\Repository\ImportFetcher;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DBALException;

class ImportMarker implements ImportMarkerInterface
{
    private Connection $connection;
    private ImportFetcher $importFetcher;

    public function __construct(
        Connection $connection,
        ImportFetcher $importDbFetcher
    ) {
        $this->connection = $connection;
        $this->importFetcher = $importDbFetcher;
    }

    /**
     * @throws DBALException
     */
    public function markViewsRefreshed(DateTimeImmutable $dateTime): void
    {
        $importRows = $this->importFetcher->findCompletedOlderThan($dateTime);
        $importIds = array_map(
            static fn (ImportRow $row) => ($row->type . '|' . $row->versionId),
            $importRows
        );

        $sql = "UPDATE imports SET views_refreshed = true WHERE concat(type, '|', version_id) IN (?)";
        $this->connection->executeStatement($sql, [$importIds], [Connection::PARAM_STR_ARRAY]);
    }
}
