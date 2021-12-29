<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\FiasTable;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use LogicException;

class FiasTableFactory
{
    private FiasTableParameters $fiasTableParameters;
    private Connection $connection;

    public function __construct(
        FiasTableParameters $fiasTableParameters,
        Connection $connection
    )
    {
        $this->fiasTableParameters = $fiasTableParameters;
        $this->connection = $connection;
    }

    /**
     * @throws Exception
     * @throws LogicException
     */    
    public function create(string $fileToken): FiasTable {
        $tableName = $this->fiasTableParameters->getTableNameByFileToken($fileToken);
        $primaryKey = $this->fiasTableParameters->getPrimaryKeyByFileToken($fileToken);
        $columnNames = $this->getTableColumnNames($tableName);

        return new FiasTable($tableName, $primaryKey, $columnNames);
    }

    /**
     * @throws Exception
     * @throws LogicException
     */
    private function getTableColumnNames(string $tableName): array
    {
        $resultSet = $this->connection->executeQuery(
            sprintf("SELECT array_to_string(tablecolumns('%s'), ',', '*')", $tableName)
        );
        $result = $resultSet->fetchOne();
        if ($result === '') {
            throw new LogicException("No columns found for the table '$tableName'");
        }

        return explode(',', $result);
    }
}