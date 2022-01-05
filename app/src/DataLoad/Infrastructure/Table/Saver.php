<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Table;

use App\DataLoad\Domain\Entity\Table;
use App\DataLoad\Domain\SaverInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use LogicException;

final class Saver implements SaverInterface
{
    private Connection $connection;
    private Factory $factory;

    public function __construct(
        Connection $connection,
        Factory $factory
    ) {
        $this->connection = $connection;
        $this->factory = $factory;
    }

    /**
     * @param array<string, string> $values
     * @throws Exception
     * @throws LogicException
     */
    public function upsert(
        string $token,
        array $values
    ): void {
        $table = $this->factory->create($token);
        $this->upsertByTable($table, $values);
    }

    /**
     * @param array<string, string> $values
     * @throws Exception
     */
    private function upsertByTable(
        Table $table,
        array $values
    ): void {
        $this->connection->executeStatement(
            sprintf(
                "SELECT upsert('%s', '%s', '{%s}',  VARIADIC ARRAY[%s])",
                $table->getName(),
                $table->getPrimaryKey(),
                $table->getColumnsAsString(),
                $table->getValuesAsString($values)
            )
        );
    }
}
