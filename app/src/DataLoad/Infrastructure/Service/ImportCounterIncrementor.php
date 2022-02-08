<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Domain\Import\Entity\Import;
use App\DataLoad\Domain\Import\Service\ImportCounterIncrementorInterface;
use App\Shared\Domain\Monitoring\MonitorInterface;
use Redis;

class ImportCounterIncrementor implements ImportCounterIncrementorInterface
{
    private Redis $redis;
    private MonitorInterface $monitor;

    public function __construct(
        MonitorInterface $monitor,
        Redis $redis
    ) {
        $this->redis = $redis;
        $this->monitor = $monitor;
    }

    /**
     * @param Import::COUNTER_FIELD_* $fieldName
     */
    public function inc(string $type, string $versionId, string $fieldName): void
    {
        $timestamp = time();
        $key = Import::buildKey($type, $versionId);
        $counterExists = $this->redis->hExists($key, Import::FIELD_CREATED_AT);

        $this->incCounter($key, $fieldName, $timestamp, $counterExists);
        $this->incMonitoring($type, $versionId, $fieldName);
    }

    private function incCounter(
        string $key,
        string $fieldName,
        int $timestamp,
        bool $counterExists
    ): void {
        $this->redis->multi();
        if (!$counterExists) {
            $this->redis->hSet($key, Import::FIELD_CREATED_AT, (string)$timestamp);
        }
        $this->redis->hIncrBy($key, $fieldName, 1);
        $this->redis->hSet($key, Import::FIELD_UPDATED_AT, (string)$timestamp);
        $this->redis->exec();
    }

    private function incMonitoring(
        string $type,
        string $versionId,
        string $fieldName
    ): void {
        $this->monitor
            ->getCounter($fieldName, '', ['type', 'version'])
            ->inc([$type, $versionId]);
    }
}
