<?php

namespace App\DTO;

class FiasParseDTO
{
    private array $payload;

    public function __construct(
        int $token,
        string $tableName,
        int $primaryKeyName,
        string $tableColumnNames,
        string $xmlTag
    ) {
        $this->payload = compact('token', 'tableName', 'primaryKeyName', 'tableColumnNames', 'xmlTag');
    }

    /**
     * @throws \JsonException
     */
    public function toAMQPMessage(): string
    {
        return json_encode($this->payload, JSON_THROW_ON_ERROR);
    }
}
