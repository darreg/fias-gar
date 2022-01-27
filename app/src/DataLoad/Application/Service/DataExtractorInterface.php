<?php

declare(strict_types=1);

namespace App\DataLoad\Application\Service;

use App\DataLoad\Domain\Version\Entity\Version;

interface DataExtractorInterface
{
    /**
     * @param Version::TYPE_* $type
     */
    public function extract(string $type, string $versionId): void;
}
