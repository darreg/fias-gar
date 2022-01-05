<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Table;

use App\DataLoad\Domain\Entity\Table;
use App\DataLoad\Infrastructure\ParameterStorage;
use Doctrine\DBAL\Exception;
use LogicException;

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
     * @throws Exception
     * @throws LogicException
     */
    public function create(string $fileToken): Table
    {
        $tableName = $this->parameterStorage->getTableNameByFileToken($fileToken);
        $primaryKey = $this->parameterStorage->getPrimaryKeyByFileToken($fileToken);
        $columnNames = $this->ddlHelper->tableColumnNames($tableName);

        return new Table($tableName, $primaryKey, $columnNames);
    }
}
