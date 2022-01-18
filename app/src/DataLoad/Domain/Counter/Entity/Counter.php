<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Counter\Entity;

use App\DataLoad\Domain\Counter\Exception\InvalidCounterKeyException;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="counter")
 * @ORM\Entity
 */
class Counter
{
    public const KEY_PREFIX = 'icounter';
    public const FIELD_CREATED_AT = 'createdAt';
    public const FIELD_UPDATED_AT = 'updatedAt';

    public const COUNTER_FIELD_TASK_NUM = 'taskNum';
    public const COUNTER_FIELD_PARSE_ERROR_NUM = 'parseErrorNum';
    public const COUNTER_FIELD_PARSE_SUCCESS_NUM = 'parseSuccessNum';
    public const COUNTER_FIELD_SAVE_ERROR_NUM = 'saveErrorNum';
    public const COUNTER_FIELD_SAVE_SUCCESS_NUM = 'saveSuccessNum';

    /**
     * @ORM\Id()
     * @ORM\Column(type="string")
     */
    private string $type;
    /**
     * @ORM\Id()
     * @ORM\Column(type="string")
     */
    private string $versionId;
    /**
     * @ORM\Column(type="integer")
     */
    private int $taskNum;
    /**
     * @ORM\Column(type="integer")
     */
    private int $parseErrorNum;
    /**
     * @ORM\Column(type="integer")
     */
    private int $parseSuccessNum;
    /**
     * @ORM\Column(type="integer")
     */
    private int $saveErrorNum;
    /**
     * @ORM\Column(type="integer")
     */
    private int $saveSuccessNum;
    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;
    /**
     * @ORM\Column(type="datetime_immutable")
     */
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
        return self::KEY_PREFIX . ':' . $type . ':' . $versionId;
    }

    /**
     * @throws InvalidCounterKeyException
     * @return list<string>
     */
    public static function splitKey(string $key): array
    {
        [$prefix, $type, $versionId] = explode(':', $key);
        if ($prefix !== self::KEY_PREFIX) {
            throw new InvalidCounterKeyException('It is not an import counter prefix');
        }
        return [$type, $versionId];
    }
}
