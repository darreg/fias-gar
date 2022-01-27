<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Version\Service;

interface VersionCovererInterface
{
    public function cover(string $versionId): void;
}
