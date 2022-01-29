<?php

declare(strict_types=1);

namespace App\DataLoad\Application\Service;

use App\DataLoad\Domain\Version\Entity\Version;

interface QueueLimiterInterface
{
    public const QUEUE_PARSE = 'parse';
    public const QUEUE_SAVE = 'save';

    /**
     * @param QueueLimiterInterface::QUEUE_* $queue
     * @param Version::TYPE_* $type
     */
    public function makePause(string $queue, string $type, string $versionId): void;
}
