<?php

declare(strict_types=1);

namespace App\DataLoad\Application\Service;

use App\DataLoad\Domain\Version\Entity\Version;

interface XmlDownloaderInterface
{
    /**
     * @param Version::TYPE_* $type
     */
    public function download(string $type, string $versionId): void;

    public function full(string $versionId): void;

    public function delta(string $versionId): void;
}
