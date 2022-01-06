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
    private TableDdlHelper $tableDdlHelper;

    public function __construct(
        ParameterStorage $parameterStorage,
        TableDdlHelper $tableDdlHelper
    ) {
        $this->parameterStorage = $parameterStorage;
        $this->tableDdlHelper = $tableDdlHelper;
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
        $columnNames = $this->tableDdlHelper->tableColumnNames($tableName);

        return new Table($tableName, $primaryKey, $columnNames);
    }
}
