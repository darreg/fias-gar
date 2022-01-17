<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Repository;

use App\DataLoad\Domain\Counter\Entity\Counter;
use App\DataLoad\Domain\Counter\Repository\CounterRepositoryInterface;
use App\Shared\Domain\Exception\EntityNotFoundException;
use DateTimeImmutable;
use Redis;

class CounterRedisRepository implements CounterRepositoryInterface
{
    private Redis $redis;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    public function find(string $key): ?Counter
    {
        if ($this->redis->hExists($key, Counter::COUNTER_FIELD_TASK_NUM) === false) {
            return null;
        }

        [$type, $versionId] = Counter::splitKey($key);
        $redisData = $this->redis->hGetAll($key);

        return new Counter(
            $type,
            $versionId,
            self::getCounter($redisData, Counter::COUNTER_FIELD_TASK_NUM),
            self::getCounter($redisData, Counter::COUNTER_FIELD_PARSE_ERROR_NUM),
            self::getCounter($redisData, Counter::COUNTER_FIELD_PARSE_SUCCESS_NUM),
            self::getCounter($redisData, Counter::COUNTER_FIELD_SAVE_ERROR_NUM),
            self::getCounter($redisData, Counter::COUNTER_FIELD_SAVE_SUCCESS_NUM),
            self::getDateTimeImmutable($redisData, Counter::FIELD_CREATED_AT),
            self::getDateTimeImmutable($redisData, Counter::FIELD_UPDATED_AT)
        );
    }

    /**
     * @throws EntityNotFoundException
     */
    public function findOrFail(string $key): Counter
    {
        $counter = $this->find($key);
        if ($counter === null) {
            throw new EntityNotFoundException('Counter is not found.');
        }

        return $counter;
    }

    public function persist(Counter $counter): void
    {
        $key = $counter->getKey();
        $this->redis->multi();
        $this->redis->hSet($key, Counter::COUNTER_FIELD_TASK_NUM, (string)$counter->getTaskNum());
        $this->redis->hSet($key, Counter::COUNTER_FIELD_PARSE_ERROR_NUM, (string)$counter->getParseErrorNum());
        $this->redis->hSet($key, Counter::COUNTER_FIELD_PARSE_SUCCESS_NUM, (string)$counter->getParseSuccessNum());
        $this->redis->hSet($key, Counter::COUNTER_FIELD_SAVE_ERROR_NUM, (string)$counter->getSaveErrorNum());
        $this->redis->hSet($key, Counter::COUNTER_FIELD_SAVE_SUCCESS_NUM, (string)$counter->getSaveSuccessNum());
        $this->redis->hSet($key, Counter::FIELD_CREATED_AT, (string)$counter->getCreatedAt()->getTimestamp());
        $this->redis->hSet($key, Counter::FIELD_UPDATED_AT, (string)time());
        $this->redis->exec();
    }

    public function remove(Counter $counter): void
    {
        $key = $counter->getKey();
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
