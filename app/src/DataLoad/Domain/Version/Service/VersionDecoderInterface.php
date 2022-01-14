<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Version\Service;

use App\DataLoad\Domain\Version\Entity\Version;

interface VersionDecoderInterface
{
    /** @return array<string, Version> */
    public function decode(string $versionsString): array;
}
