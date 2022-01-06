<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Application\Service\SaverInterface;
use App\DataLoad\Domain\Entity\Table;
use App\DataLoad\Infrastructure\Exception\RowsNotUpsertedException;
use App\DataLoad\Infrastructure\Exception\TableColumnNotFoundException;
use App\DataLoad\Infrastructure\Exception\TableNameNotFoundException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use RuntimeException;

/**
 * @psalm-suppress MethodSignatureMismatch
 */
final class TableSaver implements SaverInterface
{
    private Connection $connection;
    private TableFactory $tableFactory;

    public function __construct(
        Connection $connection,
        TableFactory $tableFactory
    ) {
        $this->connection = $connection;
        $this->tableFactory = $tableFactory;
    }

    /**
     * @param array<string, string> $values
     * @throws TableNameNotFoundException
     * @throws TableColumnNotFoundException
     * @throws RowsNotUpsertedException
     * @throws RuntimeException
     */
    public function upsert(string $token, array $values): void
    {
        $table = $this->tableFactory->create($token);
        $this->upsertByTable($table, $values);
    }

    /**
     * @param array<string, string> $values
     * @throws RowsNotUpsertedException
     * @throws RuntimeException
     */
    private function upsertByTable(Table $table, array $values): void
    {
        try {
            $affectedRows = $this->connection->executeStatement(
                sprintf(
                    "SELECT upsert('%s', '%s', '{%s}',  VARIADIC ARRAY[%s])",
                    $table->getName(),
                    $table->getPrimaryKey(),
                    $table->getColumnsAsString(),
                    $table->getValuesAsString($values)
                )
            );
        } catch (Exception $e) {
            throw new RuntimeException('', 0, $e);
        }

        if ($affectedRows === 0) {
            throw new RowsNotUpsertedException('Not one row has been affected');
        }
    }
}