<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Version\Service;

use App\DataLoad\Domain\Version\Entity\Version;

interface LoadTryIncrementorInterface
{
    public const MAX_LOAD_TRY_NUM = 3;

    /**
     * @param Version::TYPE_* $type
     */
    public function increment(string $type, string $versionId): void;
}
