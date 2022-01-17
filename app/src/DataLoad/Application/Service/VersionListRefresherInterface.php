<?php

declare(strict_types=1);

namespace App\DataLoad\Application\Service;

interface VersionListRefresherInterface
{
    public function refresh(): void;
}
