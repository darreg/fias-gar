<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Domain\Counter\Entity\Counter;
use App\DataLoad\Domain\Counter\Service\CounterIncrementorInterface;
use Redis;

class CounterIncrementor implements CounterIncrementorInterface
{
    private Redis $redis;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @param Counter::COUNTER_FIELD_* $fieldName
     */
    public function inc(string $type, string $versionId, string $fieldName): void
    {
        $key = Counter::buildKey($type, $versionId);
        $this->redis->multi();
        $this->redis->hIncrBy($key, $fieldName, 1);
        $this->redis->hSet($key, Counter::FIELD_UPDATED_AT, (string)time());
        $this->redis->exec();
    }
}
