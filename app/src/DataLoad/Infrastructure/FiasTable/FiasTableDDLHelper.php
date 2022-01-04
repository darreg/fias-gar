<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\FiasTable;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use LogicException;

final class FiasTableDDLHelper
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws Exception
     * @throws LogicException
     * @return list<string>
     */
    public function tableColumnNames(string $tableName): array
    {
        $resultSet = $this->connection->executeQuery(
            sprintf("SELECT array_to_string(tablecolumns('%s'), ',', '*')", $tableName)
        );

        $result = (string)$resultSet->fetchOne();
        if ($result === '') {
            throw new LogicException("No columns found for the table '{$tableName}'");
        }

        return explode(',', strtolower($result));
    }
}
