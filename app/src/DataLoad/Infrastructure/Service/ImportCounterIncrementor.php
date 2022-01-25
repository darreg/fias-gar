<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Domain\Import\Entity\Import;
use App\DataLoad\Domain\Import\Service\ImportCounterIncrementorInterface;
use Redis;

class ImportCounterIncrementor implements ImportCounterIncrementorInterface
{
    private Redis $redis;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @param Import::COUNTER_FIELD_* $fieldName
     */
    public function inc(string $type, string $versionId, string $fieldName): void
    {
        $key = Import::buildKey($type, $versionId);

        $createdAt = $this->redis->hGet($key, Import::FIELD_CREATED_AT);

        $this->redis->multi();
        if (!$createdAt) {
            $this->redis->hSet($key, Import::FIELD_CREATED_AT, (string)time());
        }
        $this->redis->hIncrBy($key, $fieldName, 1);
        $this->redis->hSet($key, Import::FIELD_UPDATED_AT, (string)time());
        $this->redis->exec();
    }
}
