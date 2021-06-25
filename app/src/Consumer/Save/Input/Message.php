<?php

namespace App\Consumer\Save\Input;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @psalm-suppress MissingConstructor
 */
final class Message
{
    /**
     * @Assert\NotBlank
     */
    private string $tableName;

    /**
     * @Assert\NotBlank
     */
    private string $primaryKeyName;

    /**
     * @Assert\NotBlank
     */
    private string $tableColumnNames;

    /**
     * @Assert\NotBlank
     * @var array<string, string>
     */
    private array $data;

    public static function createFromQueue(string $messageBody): self
    {
        /** @var array{tableName: string, primaryKeyName: string, tableColumnNames: string, data: array} $message */
        $message = json_decode($messageBody, true, 512, JSON_THROW_ON_ERROR);
        $result = new self();
        $result->tableName = $message['tableName'];
        $result->primaryKeyName = $message['primaryKeyName'];
        $result->tableColumnNames = $message['tableColumnNames'];
        /** @var array<string, string> $message['data'] */
        $result->data = $message['data'];

        return $result;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function getPrimaryKeyName(): string
    {
        return $this->primaryKeyName;
    }

    public function getTableColumnNames(): string
    {
        return $this->tableColumnNames;
    }

    /** @return array<string, string> */
    public function getData(): array
    {
        return $this->data;
    }
}
