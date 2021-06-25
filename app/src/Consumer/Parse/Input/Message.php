<?php

namespace App\Consumer\Parse\Input;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @psalm-suppress MissingConstructor
 */
final class Message
{
    /**
     * @Assert\Length(max = 120)
     * @Assert\NotBlank
     */
    private string $token;

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
     */
    private string $xmlTag;

    public static function createFromQueue(string $messageBody): self
    {
        /** @var array<string, string> $message */
        $message = json_decode($messageBody, true, 512, JSON_THROW_ON_ERROR);
        $result = new self();
        $result->token = $message['token'];
        $result->tableName = $message['tableName'];
        $result->primaryKeyName = $message['primaryKeyName'];
        $result->tableColumnNames = $message['tableColumnNames'];
        $result->xmlTag = $message['xmlTag'];

        return $result;
    }

    public function getToken(): string
    {
        return $this->token;
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

    public function getXmlTag(): string
    {
        return $this->xmlTag;
    }
}
