<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Domain\Import\Entity\Import;
use App\DataLoad\Domain\Import\Service\ImportMarkerInterface;
use App\DataLoad\Infrastructure\Repository\ImportRedisFetcher;
use App\DataLoad\Infrastructure\Repository\ImportRedisRepository;
use DateTimeImmutable;
use Redis;

class ImportRedisMarker implements ImportMarkerInterface
{
    private Redis $redis;
    private ImportRedisFetcher $importFetcher;

    public function __construct(
        Redis $redis,
        ImportRedisFetcher $importRedisFetcher,
    ) {
        $this->importFetcher = $importRedisFetcher;
        $this->redis = $redis;
    }

    public function markViewsRefreshed(DateTimeImmutable $dateTime): void
    {
        $importRows = $this->importFetcher->findCompletedOlderThan($dateTime);

        foreach ($importRows as $importRow) {
            $this->redis->hSet(
                Import::buildKey($importRow->type, $importRow->versionId),
                Import::FIELD_VIEWS_REFRESHED,
                ImportRedisRepository::TRUE
            );
        }
    }
}
