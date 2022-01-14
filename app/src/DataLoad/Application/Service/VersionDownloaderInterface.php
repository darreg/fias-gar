<?php

declare(strict_types=1);

namespace App\DataLoad\Application\Service;

interface VersionDownloaderInterface
{
    public function download(): void;
}
