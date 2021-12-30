<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\FiasTable;

class FiasTable
{
    private string $name;
    private string $primaryKey;
    private array $columns;
    
    public function __construct(string $name, string $primaryKey, array $columns)
    {
        $this->name = $name;
        $this->primaryKey = $primaryKey;
        $this->columns = $columns;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }
    
    public function getColumnsAsString(): string
    {
        return implode(',', $this->columns);
    }
    
    public function getValuesAsString(array $values): string
    {
        $clearValues = [];
        foreach ($this->getAllColumnValuesWithNull($values) as $value) {
            if ($value === null || $value === '') {
                $clearValues[] = 'NULL';
                continue;
            }
            $clearValues[] = "'" . str_replace("'", "''", $value) . "'";
        }

        return implode(',', $clearValues);
    }

    private function getAllColumnValuesWithNull(array $values): array
    {
        $result = [];
        foreach ($this->columns as $column) {
            $result[$column] = $values[$column] ?? null;
        }

        return $result;
    }    
}