<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Repository;

use App\DataLoad\Domain\Import\Entity\Import;
use App\DataLoad\Domain\Import\Exception\InvalidImportKeyException;
use App\DataLoad\Domain\Import\Repository\ImportRepositoryInterface;
use App\Shared\Domain\Exception\EntityNotFoundException;
use DateTimeImmutable;
use Redis;

class ImportRedisRepository implements ImportRepositoryInterface
{
    private Redis $redis;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @throws InvalidImportKeyException
     */
    public function find(string $key): ?Import
    {
        if ($this->redis->hExists($key, Import::COUNTER_FIELD_TASK_NUM) === false) {
            return null;
        }

        [$type, $versionId] = Import::splitKey($key);
        $redisData = $this->redis->hGetAll($key);

        return new Import(
            $type,
            $versionId,
            self::getCounter($redisData, Import::COUNTER_FIELD_TASK_NUM),
            self::getCounter($redisData, Import::COUNTER_FIELD_PARSE_ERROR_NUM),
            self::getCounter($redisData, Import::COUNTER_FIELD_PARSE_SUCCESS_NUM),
            self::getCounter($redisData, Import::COUNTER_FIELD_SAVE_ERROR_NUM),
            self::getCounter($redisData, Import::COUNTER_FIELD_SAVE_SUCCESS_NUM),
            self::getDateTimeImmutable($redisData, Import::FIELD_CREATED_AT),
            self::getDateTimeImmutable($redisData, Import::FIELD_UPDATED_AT)
        );
    }

    /**
     * @throws InvalidImportKeyException
     * @throws EntityNotFoundException
     */
    public function findOrFail(string $key): Import
    {
        $import = $this->find($key);
        if ($import === null) {
            throw new EntityNotFoundException('Import is not found.');
        }

        return $import;
    }

    public function findAll(): array
    {
        $keys = $this->redis->keys(Import::KEY_PREFIX . ':*');

        $imports = [];
        foreach ($keys as $key) {
            $imports[] = $this->findOrFail($key);
        }

        return $imports;
    }

    public function persist(Import $import): void
    {
        $key = $import->getKey();
        $this->redis->multi();
        $this->redis->hSet($key, Import::COUNTER_FIELD_TASK_NUM, (string)$import->getTaskNum());
        $this->redis->hSet($key, Import::COUNTER_FIELD_PARSE_ERROR_NUM, (string)$import->getParseErrorNum());
        $this->redis->hSet($key, Import::COUNTER_FIELD_PARSE_SUCCESS_NUM, (string)$import->getParseSuccessNum());
        $this->redis->hSet($key, Import::COUNTER_FIELD_SAVE_ERROR_NUM, (string)$import->getSaveErrorNum());
        $this->redis->hSet($key, Import::COUNTER_FIELD_SAVE_SUCCESS_NUM, (string)$import->getSaveSuccessNum());
        $this->redis->hSet($key, Import::FIELD_CREATED_AT, (string)$import->getCreatedAt()->getTimestamp());
        $this->redis->hSet($key, Import::FIELD_UPDATED_AT, (string)time());
        $this->redis->exec();
    }

    public function remove(Import $import): void
    {
        $key = $import->getKey();
        $this->redis->del($key);
    }

    private static function getCounter(array $redisData, string $fieldName): int
    {
        if (!\array_key_exists($fieldName, $redisData) || !$redisData[$fieldName]) {
            return 0;
        }

        return (int)$redisData[$fieldName];
    }

    private static function getDateTimeImmutable(array $redisData, string $fieldName): DateTimeImmutable
    {
        if (!\array_key_exists($fieldName, $redisData) || !$redisData[$fieldName]) {
            return new DateTimeImmutable();
        }

        return (new DateTimeImmutable())->setTimestamp((int)$redisData[$fieldName]);
    }
}
