<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Version\Service;

interface VersionLoaderInterface
{
    public function load(): string;
}
