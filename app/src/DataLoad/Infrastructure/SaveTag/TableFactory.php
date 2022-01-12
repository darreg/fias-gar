<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\SaveTag;

use App\DataLoad\Domain\Table\Entity\Table;
use App\DataLoad\Domain\Table\Exception\TableColumnNotFoundException;
use App\DataLoad\Domain\Table\Exception\TableNameNotFoundException;
use App\DataLoad\Domain\Table\Service\TableColumnerInterface;
use App\DataLoad\Infrastructure\Shared\ParameterStorage;
use RuntimeException;

final class TableFactory
{
    private ParameterStorage $parameterStorage;
    private TableColumnerInterface $tableColumnService;

    public function __construct(
        ParameterStorage $parameterStorage,
        TableColumnerInterface $tableColumnService
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
        $columnNames = $this->tableColumnService->getNames($tableName);

        return new Table($tableName, $primaryKey, $columnNames);
    }
}
