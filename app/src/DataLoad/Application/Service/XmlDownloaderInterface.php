<?php

declare(strict_types=1);

namespace App\DataLoad\Application\Service;

interface XmlDownloaderInterface
{
    public const TYPE_FULL = 'full';
    public const TYPE_DELTA = 'delta';

    /**
     * @param XmlDownloaderInterface::TYPE_* $type
     */
    public function download(string $type, string $versionId): void;

    public function full(string $versionId): void;

    public function delta(string $versionId): void;
}
