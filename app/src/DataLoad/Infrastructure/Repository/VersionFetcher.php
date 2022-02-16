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
     * @return array<int, VersionRow>
     */
    public function findAll(): array
    {
        $queryBuilder = $this->connection->createQueryBuilder()
            ->select('id, full_has_xml, delta_has_xml')
            ->from('version')
            ->orderBy('date', 'ASC')
            ->executeQuery();

        $results = $queryBuilder->fetchAllAssociative();

        if (!$results) {
            return [];
        }

        $rows = [];
        /** @var array $result */
        foreach ($results as $result) {
            $rows[] = new VersionRow(
                (string)$result['id'],
                (bool)$result['full_has_xml'],
                (bool)$result['delta_has_xml']
            );
        }

        return $rows;
    }

    /**
     * @throws DBALException
     */
    public function findOldestUncoveredDeltaVersion(): ?VersionRow
    {
        $queryBuilder = $this->connection->createQueryBuilder()
            ->select('id, full_has_xml, delta_has_xml')
            ->from('version')
            ->andWhere('delta_has_xml = true')
            ->andWhere('covered = false AND delta_broken_url = false')
            ->andWhere('delta_loaded_at is null')
            ->orderBy('date', 'ASC')
            ->setMaxResults(1)
            ->executeQuery();

        $result = $queryBuilder->fetchAssociative();

        if (!$result) {
            return null;
        }

        return new VersionRow(
            (string)$result['id'],
            (bool)$result['full_has_xml'],
            (bool)$result['delta_has_xml']
        );
    }

    /**
     * @throws DBALException
     */
    public function findNewestUnloadedFullVersion(): ?VersionRow
    {
        $queryBuilder = $this->connection->createQueryBuilder()
            ->select('id, full_has_xml, delta_has_xml')
            ->from('version')
            ->andWhere('full_has_xml = true')
            ->andWhere('covered = false AND full_broken_url = false')
            ->andWhere('full_loaded_at is null')
            ->orderBy('date', 'DESC')
            ->setMaxResults(1)
            ->executeQuery();

        $result = $queryBuilder->fetchAssociative();

        if (!$result) {
            return null;
        }

        return new VersionRow(
            (string)$result['id'],
            (bool)$result['full_has_xml'],
            (bool)$result['delta_has_xml']
        );
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
            ->select('id, full_has_xml, delta_has_xml')
            ->from('version')
            ->andWhere('covered = false')
            ->andWhere('id <= :id')
            ->setParameter('id', $id, Types::STRING)
            ->executeQuery();

        $versionRow = [];
        foreach ($queryBuilder->fetchAllAssociative() as $row) {
            $versionRow[] = new VersionRow(
                (string)$row['id'],
                (bool)$row['full_has_xml'],
                (bool)$row['delta_has_xml']
            );
        }

        return $versionRow;
    }
}
