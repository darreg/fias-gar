<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use LogicException;

final class DatabaseFunctionCaller
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws Exception
     * @throws LogicException
     */
    public function tableColumnNames(string $tableName): array
    {
        $resultSet = $this->connection->executeQuery(
            sprintf("SELECT array_to_string(tablecolumns('%s'), ',', '*')", $tableName)
        );
        $result = $resultSet->fetchOne();
        if ($result === '') {
            throw new LogicException("No columns found for the table '{$tableName}'");
        }

        return explode(',', strtolower($result));
    }
}
