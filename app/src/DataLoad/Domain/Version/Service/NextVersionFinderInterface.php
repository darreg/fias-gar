<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Version\Service;

use App\DataLoad\Domain\Version\Entity\Version;

interface NextVersionFinderInterface
{
    /**
     * @param Version::TYPE_* $type
     */
    public function next(string $type): ?string;

    public function nextDelta(): ?string;

    public function nextFull(): ?string;
}
