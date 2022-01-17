<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Counter\Entity;

use DateTimeImmutable;

class Counter
{
    public const FIELD_TYPE = 'type';
    public const FIELD_CREATED_AT = 'createdAt';
    public const FIELD_UPDATED_AT = 'updatedAt';

    public const COUNTER_FIELD_TASK_NUM = 'taskNum';
    public const COUNTER_FIELD_PARSE_ERROR_NUM = 'parseErrorNum';
    public const COUNTER_FIELD_PARSE_SUCCESS_NUM = 'parseSuccessNum';
    public const COUNTER_FIELD_SAVE_ERROR_NUM = 'saveErrorNum';
    public const COUNTER_FIELD_SAVE_SUCCESS_NUM = 'saveSuccessNum';

    private string $type;
    private string $versionId;
    private int $taskNum;
    private int $parseErrorNum;
    private int $parseSuccessNum;
    private int $saveErrorNum;
    private int $saveSuccessNum;
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable $updatedAt;

    public function __construct(
        string $type,
        string $versionId,
        int $taskNum = 0,
        int $parseErrorNum = 0,
        int $parseSuccessNum = 0,
        int $saveErrorNum = 0,
        int $saveSuccessNum = 0,
        DateTimeImmutable $createdAt = new DateTimeImmutable(),
        DateTimeImmutable $updatedAt = new DateTimeImmutable()
    ) {
        $this->type = $type;
        $this->versionId = $versionId;
        $this->taskNum = $taskNum;
        $this->parseErrorNum = $parseErrorNum;
        $this->parseSuccessNum = $parseSuccessNum;
        $this->saveErrorNum = $saveErrorNum;
        $this->saveSuccessNum = $saveSuccessNum;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getVersionId(): string
    {
        return $this->versionId;
    }

    public function getTaskNum(): int
    {
        return $this->taskNum;
    }

    public function getParseErrorNum(): int
    {
        return $this->parseErrorNum;
    }

    public function getParseSuccessNum(): int
    {
        return $this->parseSuccessNum;
    }

    public function getSaveErrorNum(): int
    {
        return $this->saveErrorNum;
    }

    public function getSaveSuccessNum(): int
    {
        return $this->saveSuccessNum;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getKey(): string
    {
        return self::buildKey($this->type, $this->versionId);
    }

    public static function buildKey(string $type, string $versionId): string
    {
        return $type . ':' . $versionId;
    }

    /**
     * @return list<string>
     */
    public static function splitKey(string $key): array
    {
        return explode(':', $key);
    }
}
