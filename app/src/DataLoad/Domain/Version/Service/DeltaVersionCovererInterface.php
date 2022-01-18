<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Version\Service;

interface DeltaVersionCovererInterface
{
    public function cover(string $versionId): void;
}
