<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Table;

use App\DataLoad\Domain\Entity\Table;
use App\DataLoad\Infrastructure\Exception\TableColumnNotFoundException;
use App\DataLoad\Infrastructure\Exception\TableNameNotFoundException;
use App\DataLoad\Infrastructure\ParameterStorage;
use RuntimeException;

final class Factory
{
    private ParameterStorage $parameterStorage;
    private DdlHelper $ddlHelper;

    public function __construct(
        ParameterStorage $parameterStorage,
        DdlHelper $ddlHelper
    ) {
        $this->parameterStorage = $parameterStorage;
        $this->ddlHelper = $ddlHelper;
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
        $columnNames = $this->ddlHelper->tableColumnNames($tableName);

        return new Table($tableName, $primaryKey, $columnNames);
    }
}
