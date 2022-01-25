<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Repository;

use App\DataLoad\Domain\Import\ReadModel\ImportRow;
use App\DataLoad\Domain\Import\Repository\ImportFetcherInterface;
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
     * @return array<int, ImportRow>
     */
    public function findAll(): array
    {
        $queryBuilder = $this->connection->createQueryBuilder()
            ->select()
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
     * @return array<int, ImportRow>
     */
    public function findCompleted(): array
    {
        return $this->findAll();
    }

    /**
     * @throws DBALException
     */
    public function isIncompleteExists(): bool
    {
        $imports = $this->findCompleted();
        return \count($imports) > 0;
    }
}
