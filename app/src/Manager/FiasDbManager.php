<?php

declare(strict_types=1);

namespace App\Manager;

use Doctrine\DBAL\Logging\SQLLogger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;

final class FiasDbManager
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function refreshView(string $viewName): void
    {
        $query = $this->em->createNativeQuery(
            'REFRESH MATERIALIZED VIEW ' . $viewName,
            new ResultSetMapping()
        );
        $query->execute();
        $this->em->clear();
    }

    public function upsert(
        string $tableName,
        string $primaryKey,
        array $values,
        ?string $columnNames = null
    ): void {
        $query = $this->em->createNativeQuery(
            'SELECT upsert(\'' .
            $tableName . '\', \'' .
            $primaryKey . '\', ' .
            (!empty($columnNames) ? '\'' . $columnNames . '\'' : 'NULL') .
            ',  VARIADIC ARRAY[' . $this->buildArrayString($values) . ']' .
            ');',
            new ResultSetMapping()
        );
        $query->execute();
        $this->em->clear();
    }

    public function upsertFileVersion(string $token, string $version): void
    {
        $this->upsert('fias_version', 'file_token', [$token, $version]);
    }

    public function getTableColumnsAsString(string $tableName): ?string
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('tablecolumns', 'tableColumns');

        $query = $this->em->createNativeQuery(
            'SELECT tablecolumns(\'' . $tableName . '\');',
            $rsm
        );

        $result = $query->getScalarResult();
        if (empty($result) || empty($result[0])) {
            return null;
        }

        $result = $result[0];

        if (empty($result['tableColumns'])) {
            return null;
        }

        return $result['tableColumns'];
    }

    public function disableSqlLogger(): ?SQLLogger
    {
        $configuration = $this->em->getConnection()->getConfiguration();
        if ($configuration === null) {
            return null;
        }
        $sqlLogger = $configuration->getSQLLogger();
        $configuration->setSQLLogger();

        return $sqlLogger;
    }

    public function enableSqlLogger(?SQLLogger $sqlLogger): void
    {
        $configuration = $this->em->getConnection()->getConfiguration();
        if ($configuration === null) {
            return;
        }

        $configuration->setSQLLogger($sqlLogger);
    }

    public static function rebuildTagData(array $tagData, array $columnNames): array
    {
        $result = [];

        foreach ($columnNames as $columnName) {
            $result[$columnName] = $tagData[$columnName] ?? null;
        }

        return $result;
    }

    public static function tableColumnsStringtoArray(
        string $columnsAsString,
        string $strFunction = 'strtoupper'
    ): array {
        if (empty($columnsAsString)) {
            return [];
        }

        $columnsAsString = trim($columnsAsString, '{}');
        $columns = explode(',', $columnsAsString);
        if (\function_exists($strFunction)) {
            $columns = array_map($strFunction, array_values($columns));
        }

        return $columns;
    }

    private function buildArrayString(array $values): string
    {
        $clearValues = [];
        foreach ($values as $value) {
            if ($value !== null && $value !== '') {
                $clearValues[] = "'" . str_replace("'", "''", $value) . "'";
            } else {
                $clearValues[] = 'NULL';
            }
        }

        return implode(',', $clearValues);
    }
}