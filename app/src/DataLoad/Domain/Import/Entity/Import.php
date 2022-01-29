<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Import\Entity;

use App\DataLoad\Domain\Import\Exception\InvalidImportKeyException;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="imports")
 * @ORM\Entity
 */
class Import
{
    public const KEY_PREFIX = 'import';

    public const FIELD_TYPE = 'type';
    public const FIELD_VERSION_ID = 'version_id';
    public const FIELD_CREATED_AT = 'created_at';
    public const FIELD_UPDATED_AT = 'updated_at';
    public const FIELD_VIEWS_REFRESHED = 'views_refreshed';

    public const COUNTER_FIELD_PARSE_TASK_NUM = 'parse_task_num';
    public const COUNTER_FIELD_PARSE_ERROR_NUM = 'parse_error_num';
    public const COUNTER_FIELD_PARSE_SUCCESS_NUM = 'parse_success_num';
    public const COUNTER_FIELD_SAVE_TASK_NUM = 'save_task_num';
    public const COUNTER_FIELD_SAVE_ERROR_NUM = 'save_error_num';
    public const COUNTER_FIELD_SAVE_SUCCESS_NUM = 'save_success_num';

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
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private int $parseTaskNum;
    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private int $parseErrorNum;
    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private int $parseSuccessNum;
    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private int $saveTaskNum;
    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private int $saveErrorNum;
    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private int $saveSuccessNum;
    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private bool $viewsRefreshed;
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
        int $parseTaskNum = 0,
        int $parseErrorNum = 0,
        int $parseSuccessNum = 0,
        int $saveTaskNum = 0,
        int $saveErrorNum = 0,
        int $saveSuccessNum = 0,
        bool $viewsRefreshed = false,
        DateTimeImmutable $createdAt = new DateTimeImmutable(),
        DateTimeImmutable $updatedAt = new DateTimeImmutable()
    ) {
        $this->type = $type;
        $this->versionId = $versionId;
        $this->parseTaskNum = $parseTaskNum;
        $this->parseErrorNum = $parseErrorNum;
        $this->parseSuccessNum = $parseSuccessNum;
        $this->saveTaskNum = $saveTaskNum;
        $this->saveErrorNum = $saveErrorNum;
        $this->saveSuccessNum = $saveSuccessNum;
        $this->viewsRefreshed = $viewsRefreshed;
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

    public function getParseTaskNum(): int
    {
        return $this->parseTaskNum;
    }

    public function getParseErrorNum(): int
    {
        return $this->parseErrorNum;
    }

    public function getParseSuccessNum(): int
    {
        return $this->parseSuccessNum;
    }

    public function getSaveTaskNum(): int
    {
        return $this->saveTaskNum;
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

    public function isViewsRefreshed(): bool
    {
        return $this->viewsRefreshed;
    }

    public function getParseQueueLength(): int
    {
        return $this->parseTaskNum - $this->parseSuccessNum - $this->parseErrorNum;
    }

    public function getSaveQueueLength(): int
    {
        return $this->saveTaskNum - $this->saveSuccessNum - $this->saveErrorNum;
    }

    public function toArray(): array
    {
        return [
            self::FIELD_TYPE => $this->type,
            self::FIELD_VERSION_ID => $this->versionId,
            self::COUNTER_FIELD_PARSE_TASK_NUM => $this->parseTaskNum,
            self::COUNTER_FIELD_PARSE_ERROR_NUM => $this->parseErrorNum,
            self::COUNTER_FIELD_PARSE_SUCCESS_NUM => $this->parseSuccessNum,
            self::COUNTER_FIELD_SAVE_TASK_NUM => $this->saveTaskNum,
            self::COUNTER_FIELD_SAVE_ERROR_NUM => $this->saveErrorNum,
            self::COUNTER_FIELD_SAVE_SUCCESS_NUM => $this->saveSuccessNum,
            self::FIELD_VIEWS_REFRESHED => $this->viewsRefreshed,
            self::FIELD_CREATED_AT => $this->createdAt->format('Y-m-d H:i:s'),
            self::FIELD_UPDATED_AT => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }

    public static function isFinished(
        int $parseTaskNum,
        int $saveSuccessNum,
        int $saveErrorNum,
        int $parseErrorNum
    ): bool {
        return ($saveSuccessNum + $saveErrorNum + $parseErrorNum) >= $parseTaskNum;
    }

    public static function buildKey(string $type, string $versionId): string
    {
        return self::KEY_PREFIX . ':' . $type . ':' . $versionId;
    }

    /**
     * @throws InvalidImportKeyException
     * @return list<string>
     */
    public static function splitKey(string $key): array
    {
        [$prefix, $type, $versionId] = explode(':', $key);
        if ($prefix !== self::KEY_PREFIX) {
            throw new InvalidImportKeyException('It is not an import prefix');
        }
        return [$type, $versionId];
    }
}
