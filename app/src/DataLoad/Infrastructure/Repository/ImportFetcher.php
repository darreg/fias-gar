<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Repository;

use App\DataLoad\Domain\Import\ReadModel\ImportRow;
use App\DataLoad\Domain\Import\Repository\ImportFetcherInterface;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DBALException;

class ImportFetcher implements ImportFetcherInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws DBALException
     * @return list<ImportRow>
     */
    public function findAll(): array
    {
        $queryBuilder = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('imports')
            ->executeQuery();

        $results = $queryBuilder->fetchAllAssociative();

        if (!$results) {
            return [];
        }

        $rows = [];
        /** @var array $result */
        foreach ($results as $result) {
            $rows[] = ImportRow::fromArray($result);
        }

        return $rows;
    }

    public function findUncompleted(): array
    {
        return [];
    }

    /**
     * @throws DBALException
     * @return list<ImportRow>
     */
    public function findCompletedOlderThan(DateTimeImmutable $dateTime): array
    {
        $queryBuilder = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('imports')
            ->andWhere('updated_at < :date')
            ->setParameter('date', $dateTime->format('Y-m-d H:i:s'))
            ->executeQuery();

        $results = $queryBuilder->fetchAllAssociative();

        if (!$results) {
            return [];
        }

        $rows = [];
        /** @var array $result */
        foreach ($results as $result) {
            $rows[] = ImportRow::fromArray($result);
        }

        return $rows;
    }

    /**
     * @throws DBALException
     * @return list<ImportRow>
     */
    public function findCompleted(): array
    {
        return $this->findAll();
    }

    public function isUncompletedExists(): bool
    {
        $imports = $this->findUncompleted();
        return \count($imports) > 0;
    }
}
