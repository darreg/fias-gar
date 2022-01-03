<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\FiasTable;

use Doctrine\DBAL\Exception;
use LogicException;

final class FiasTableFactory
{
    private FiasTableParameter $fiasTableParameters;
    private FiasTableDDLHelper $fiasTableDDLHelper;

    public function __construct(
        FiasTableParameter $fiasTableParameters,
        FiasTableDDLHelper $fiasTableDDLHelper
    ) {
        $this->fiasTableParameters = $fiasTableParameters;
        $this->fiasTableDDLHelper = $fiasTableDDLHelper;
    }

    /**
     * @throws Exception
     * @throws LogicException
     */
    public function create(string $fileToken): FiasTable
    {
        $tableName = $this->fiasTableParameters->getTableNameByFileToken($fileToken);
        $primaryKey = $this->fiasTableParameters->getPrimaryKeyByFileToken($fileToken);
        $columnNames = $this->fiasTableDDLHelper->tableColumnNames($tableName);

        return new FiasTable($tableName, $primaryKey, $columnNames);
    }
}
