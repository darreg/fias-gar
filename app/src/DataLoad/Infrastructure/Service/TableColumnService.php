<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Infrastructure\Exception\TableColumnNotFoundException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use RuntimeException;

final class TableColumnService
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws RuntimeException
     * @throws TableColumnNotFoundException
     * @return list<string>
     */
    public function getColumnNames(string $tableName): array
    {
        try {
            $resultSet = $this->connection->executeQuery(
                sprintf("SELECT array_to_string(tablecolumns('%s'), ',', '*')", $tableName)
            );
            $result = (string)$resultSet->fetchOne();
        } catch (Exception $e) {
            throw new RuntimeException('', 0, $e);
        }

        if ($result === '') {
            throw new TableColumnNotFoundException("No columns found for the table '{$tableName}'");
        }

        return explode(',', strtolower($result));
    }
}
