<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\FiasTable;

class FiasTable
{
    private string $name;
    private string $primaryKey;
    private array $columns;

    /**
     * @param string $name
     * @param string $primaryKey
     * @param array $columns
     */
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
}