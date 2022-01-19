<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Repository;

use App\DataLoad\Domain\Version\ReadModel\VersionRow;
use App\DataLoad\Domain\Version\Repository\VersionFetcherInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\DBAL\Types\Types;

class VersionFetcher implements VersionFetcherInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws DBALException
     */
    public function findOldestUncoveredDeltaVersion(): ?VersionRow
    {
        $queryBuilder = $this->connection->createQueryBuilder()
            ->select('id')
            ->from('version')
            ->andWhere('has_delta_xml = true')
            ->andWhere('covered = false')
            ->andWhere('delta_loaded_at is null')
            ->orderBy('date', 'ASC')
            ->setMaxResults(1)
            ->executeQuery();

        $result = $queryBuilder->fetchAssociative();

        if (!$result) {
            return null;
        }

        return new VersionRow((string)$result['id']);
    }

    /**
     * @throws DBALException
     */
    public function findNewestUnloadedFullVersion(): ?VersionRow
    {
        $queryBuilder = $this->connection->createQueryBuilder()
            ->select('id')
            ->from('version')
            ->andWhere('has_full_xml = true')
            ->andWhere('full_loaded_at is null')
            ->orderBy('date', 'DESC')
            ->setMaxResults(1)
            ->executeQuery();

        $result = $queryBuilder->fetchAssociative();

        if (!$result) {
            return null;
        }

        return new VersionRow((string)$result['id']);
    }

    /**
     * @throws DBALException
     * @return list<VersionRow>
     */
    public function findPrevious(string $id): array
    {
        if ($id === '') {
            throw new DBALException\InvalidArgumentException();
        }

        $queryBuilder = $this->connection->createQueryBuilder()
            ->select('id')
            ->from('version')
            ->andWhere('covered = false')
            ->andWhere('id <= :id')
            ->setParameter('id', $id, Types::STRING)
            ->executeQuery();

        $versionRow = [];
        foreach ($queryBuilder->fetchAllAssociative() as $row) {
            $versionRow[] = new VersionRow((string)$row['id']);
        }

        return $versionRow;
    }
}
