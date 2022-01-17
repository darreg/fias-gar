<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Domain\Table\Entity\Table;
use App\DataLoad\Domain\Table\Exception\TableColumnNotFoundException;
use App\DataLoad\Domain\Table\Exception\TableNameNotFoundException;
use App\DataLoad\Domain\Tag\Service\TagSaverInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use RuntimeException;

/**
 * @psalm-suppress MethodSignatureMismatch
 */
final class TagSaver implements TagSaverInterface
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
     * @throws RuntimeException
     */
    public function upsert(string $token, array $values): void
    {
        $table = $this->tableFactory->create($token);
        $this->upsertByTable($table, $values);
    }

    /**
     * @param array<string, string> $values
     * @throws RuntimeException
     */
    private function upsertByTable(Table $table, array $values): void
    {
        try {
            $this->connection->executeStatement(
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
    }
}
