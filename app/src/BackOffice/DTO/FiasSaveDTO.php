<?php

namespace App\BackOffice\DTO;

class FiasSaveDTO
{
    private array $payload;

    public function __construct(
        string $tableName,
        string $primaryKeyName,
        string $tableColumnNames,
        array $data
    ) {
        $this->payload = compact('tableName', 'primaryKeyName', 'tableColumnNames', 'data');
    }

    /**
     * @throws \JsonException
     */
    public function toAMQPMessage(): string
    {
        return json_encode($this->payload, JSON_THROW_ON_ERROR);
    }
}
