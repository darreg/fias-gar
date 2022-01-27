<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\ZipFile\Service;

use App\DataLoad\Domain\Version\Entity\Version;

interface ZipFileNamerInterface
{
    /**
     * @param Version::TYPE_* $type
     */
    public function getFileName(string $type, string $versionId): string;
}
