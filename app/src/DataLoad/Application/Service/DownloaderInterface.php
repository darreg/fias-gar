<?php

declare(strict_types=1);

namespace App\DataLoad\Application\Service;

interface DownloaderInterface
{
    public const TYPE_FULL = 'full';
    public const TYPE_DELTA = 'delta';

    /**
     * @param DownloaderInterface::TYPE_* $type
     */
    public function download(string $type, string $versionId): void;

    public function full(string $versionId): void;

    public function delta(string $versionId): void;
}
