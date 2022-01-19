<?php

declare(strict_types=1);

namespace App\Shared\Domain\Persistence;

use App\Shared\Domain\Aggregate\AggregateRootInterface;

interface FlusherInterface
{
    public function flush(AggregateRootInterface ...$roots): void;
}
