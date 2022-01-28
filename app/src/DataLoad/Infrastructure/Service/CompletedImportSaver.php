<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Domain\Import\Entity\Import;
use App\DataLoad\Domain\Import\ReadModel\ImportRow;
use App\DataLoad\Infrastructure\Exception\CompletedImportSaverException;
use App\DataLoad\Infrastructure\Repository\ImportFetcher;
use App\DataLoad\Infrastructure\Repository\ImportRedisFetcher;
use App\DataLoad\Infrastructure\Repository\ImportRepository;
use App\Shared\Domain\Persistence\FlusherInterface;
use Exception;

class CompletedImportSaver
{
    private ImportRedisFetcher $redisFetcher;
    private ImportRepository $importRepository;
    private ImportFetcher $dbFetcher;
    private FlusherInterface $flusher;

    public function __construct(
        ImportRedisFetcher $redisFetcher,
        ImportFetcher $dbFetcher,
        ImportRepository $importRepository,
        FlusherInterface $flusher
    ) {
        $this->redisFetcher = $redisFetcher;
        $this->importRepository = $importRepository;
        $this->dbFetcher = $dbFetcher;
        $this->flusher = $flusher;
    }

    /** @throws CompletedImportSaverException */
    public function save(): void
    {
        try {
            $redisImportRows = $this->redisFetcher->findCompleted();
            $redisImportIds = array_map(static fn (ImportRow $row) => $row->type . '|' . $row->versionId, $redisImportRows);
            $dbImportRows = $this->dbFetcher->findCompleted();
            $dbImportIds = array_map(static fn (ImportRow $row) => $row->type . '|' . $row->versionId, $dbImportRows);

            $newImportIds = array_diff($redisImportIds, $dbImportIds);

            foreach ($redisImportRows as $row) {
                $key = $row->type . '|' . $row->versionId;
                if (!\in_array($key, $newImportIds, true)) {
                    continue;
                }

                $this->importRepository->persist(new Import(
                    $row->type,
                    $row->versionId,
                    $row->parseTaskNum,
                    $row->parseErrorNum,
                    $row->parseSuccessNum,
                    $row->saveTaskNum,
                    $row->saveErrorNum,
                    $row->saveSuccessNum,
                    $row->viewsRefreshed,
                    $row->createdAt,
                    $row->updatedAt
                ));
            }

            $this->flusher->flush();
        } catch (Exception $e) {
            throw new CompletedImportSaverException('Error saving completed imports to database', 0, $e);
        }
    }
}
