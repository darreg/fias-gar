<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\FiasTable;

use App\Shared\Infrastructure\Doctrine\DatabaseFunctionCaller;
use Doctrine\DBAL\Exception;
use LogicException;

final class FiasTableFactory
{
    private FiasTableParameter $fiasTableParameters;
    private DatabaseFunctionCaller $databaseFunctionCaller;

    public function __construct(
        FiasTableParameter $fiasTableParameters,
        DatabaseFunctionCaller $databaseFunctionCaller
    ) {
        $this->fiasTableParameters = $fiasTableParameters;
        $this->databaseFunctionCaller = $databaseFunctionCaller;
    }

    /**
     * @throws Exception
     * @throws LogicException
     */
    public function create(string $fileToken): FiasTable
    {
        $tableName = $this->fiasTableParameters->getTableNameByFileToken($fileToken);
        $primaryKey = $this->fiasTableParameters->getPrimaryKeyByFileToken($fileToken);
        $columnNames = $this->databaseFunctionCaller->tableColumnNames($tableName);

        return new FiasTable($tableName, $primaryKey, $columnNames);
    }
}
