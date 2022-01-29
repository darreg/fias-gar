<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Application\Service\QueueLimiterInterface;
use App\DataLoad\Domain\Import\Repository\ImportRepositoryInterface;
use App\DataLoad\Domain\Version\Entity\Version;

class QueueLimiter implements QueueLimiterInterface
{
    private ImportRepositoryInterface $importRepository;
    private int $queueParseMaxLength;
    private int $queueSaveMaxLength;
    /** @psalm-var positive-int */
    private int $queueLengthLimitSleepTime;

    /**
     * @param positive-int $queueLengthLimitSleepTime
     */
    public function __construct(
        ImportRepositoryInterface $importRepository,
        int $queueParseMaxLength,
        int $queueSaveMaxLength,
        int $queueLengthLimitSleepTime
    ) {
        $this->importRepository = $importRepository;
        $this->queueParseMaxLength = $queueParseMaxLength;
        $this->queueSaveMaxLength = $queueSaveMaxLength;
        $this->queueLengthLimitSleepTime = $queueLengthLimitSleepTime;
    }

    /**
     * @param QueueLimiterInterface::QUEUE_* $queue
     * @param Version::TYPE_* $type
     */
    public function makePause(string $queue, string $type, string $versionId): void
    {
        $queueMaxLength = match ($queue) {
            QueueLimiterInterface::QUEUE_PARSE => $this->queueParseMaxLength,
            QueueLimiterInterface::QUEUE_SAVE => $this->queueSaveMaxLength
        };

        $queueLength = $this->getQueueLength($queue, $type, $versionId);
        while ($queueLength > $queueMaxLength) {
            sleep($this->queueLengthLimitSleepTime);
            $queueLength = $this->getQueueLength($queue, $type, $versionId);
        }
    }

    /**
     * @param QueueLimiterInterface::QUEUE_* $queue
     * @param Version::TYPE_* $type
     */
    private function getQueueLength(string $queue, string $type, string $versionId): int
    {
        $import = $this->importRepository->findOrFail($type, $versionId);
        return match ($queue) {
            QueueLimiterInterface::QUEUE_PARSE => $import->getParseQueueLength(),
            QueueLimiterInterface::QUEUE_SAVE => $import->getSaveQueueLength()
        };
    }
}
