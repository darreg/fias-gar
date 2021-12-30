<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\FiasTable;

use App\DataLoad\Domain\FiasTableSaverInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use LogicException;

class FiasTableSaver implements FiasTableSaverInterface
{
    private Connection $connection;
    private FiasTableFactory $fiasTableFactory;

    public function __construct(
        Connection $connection,
        FiasTableFactory $fiasTableFactory
    )
    {
        $this->connection = $connection;
        $this->fiasTableFactory = $fiasTableFactory;
    }

    /**
     * @throws Exception
     * @throws LogicException
     */
    public function upsert(
        string $token, 
        array $values
    ): void
    {
        $fiasTable = $this->fiasTableFactory->create($token);
        $this->upsertByFiasTable($fiasTable, $values);
    }

    /**
     * @throws Exception
     */
    private function upsertByFiasTable(
        FiasTable $fiasTable,
        array $values
    ): void {
        $this->connection->executeStatement(
            sprintf(
                "SELECT upsert('%s', '%s', '{%s}',  VARIADIC ARRAY[%s])",
                $fiasTable->getName(),
                $fiasTable->getPrimaryKey(),
                $fiasTable->getColumnsAsString(),
                $fiasTable->getValuesAsString($values)
            )
        );
    }
}