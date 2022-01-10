<?php

declare(strict_types=1);

namespace App\DataLoad\Application\Service;

interface DownloaderInterface
{
    public function downloadFull(string $versionId): void;

    public function downloadDelta(string $versionId): void;
}
