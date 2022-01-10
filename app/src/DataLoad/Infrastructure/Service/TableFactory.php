<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Domain\Entity\Table;
use App\DataLoad\Infrastructure\Exception\TableColumnNotFoundException;
use App\DataLoad\Infrastructure\Exception\TableNameNotFoundException;
use RuntimeException;

final class TableFactory
{
    private ParameterStorage $parameterStorage;
    private TableColumnService $tableColumnService;

    public function __construct(
        ParameterStorage $parameterStorage,
        TableColumnService $tableColumnService
    ) {
        $this->parameterStorage = $parameterStorage;
        $this->tableColumnService = $tableColumnService;
    }

    /**
     * @throws TableNameNotFoundException
     * @throws TableColumnNotFoundException
     * @throws RuntimeException
     */
    public function create(string $fileToken): Table
    {
        $tableName = $this->parameterStorage->getTableNameByFileToken($fileToken);
        $primaryKey = $this->parameterStorage->getPrimaryKeyByFileToken($fileToken);
        $columnNames = $this->tableColumnService->getColumnNames($tableName);

        return new Table($tableName, $primaryKey, $columnNames);
    }
}
