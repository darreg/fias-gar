<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Repository;

use App\DataLoad\Domain\Import\Entity\Import;
use App\DataLoad\Domain\Import\ReadModel\ImportRow;
use App\DataLoad\Domain\Import\Repository\ImportFetcherInterface;
use DateTimeImmutable;
use Redis;

class ImportRedisFetcher implements ImportFetcherInterface
{
    private Redis $redis;
    private ImportRedisRepository $importRedisRepository;

    public function __construct(
        Redis $redis,
        ImportRedisRepository $importRedisRepository
    ) {
        $this->redis = $redis;
        $this->importRedisRepository = $importRedisRepository;
    }

    /**
     * @return list<ImportRow>
     */
    public function findAll(): array
    {
        $keys = $this->redis->keys(Import::KEY_PREFIX . ':*');

        $imports = [];
        foreach ($keys as $key) {
            $import = $this->importRedisRepository->findOrFail(...Import::splitKey($key));
            $imports[] = ImportRow::fromArray($import->toArray());
        }

        return $imports;
    }

    /**
     * @return list<ImportRow>
     */
    public function findUncompleted(): array
    {
        $uncompletedImports = [];
        $imports = $this->findAll();
        foreach ($imports as $import) {
            if (Import::isFinished(
                $import->parseTaskNum,
                $import->saveSuccessNum,
                $import->saveErrorNum,
                $import->parseErrorNum
            )) {
                continue;
            }
            if (
                (time() - $import->updatedAt->getTimestamp()) > ImportFetcherInterface::EXPIRE_INTERVAL
            ) {
                continue;
            }
            $uncompletedImports[] = $import;
        }

        return $uncompletedImports;
    }

    /**
     * @return list<ImportRow>
     */
    public function findCompleted(): array
    {
        $completedImports = [];
        $imports = $this->findAll();
        foreach ($imports as $import) {
            if (!Import::isFinished(
                $import->parseTaskNum,
                $import->saveSuccessNum,
                $import->saveErrorNum,
                $import->parseErrorNum
            )) {
                continue;
            }
            $completedImports[] = $import;
        }

        return $completedImports;
    }

    /**
     * @return list<ImportRow>
     */
    public function findCompletedOlderThan(DateTimeImmutable $dateTime): array
    {
        $olderImports = [];
        $imports = $this->findCompleted();
        foreach ($imports as $import) {
            if ($import->updatedAt->getTimestamp() > $dateTime->getTimestamp()) {
                continue;
            }
            $olderImports[] = $import;
        }

        return $olderImports;
    }

    public function isUncompletedExists(): bool
    {
        $imports = $this->findUncompleted();
        return \count($imports) > 0;
    }
}
