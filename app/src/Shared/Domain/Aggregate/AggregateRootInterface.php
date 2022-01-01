<?php

declare(strict_types=1);

namespace App\Shared\Domain\Aggregate;

interface AggregateRootInterface
{
    public function clearEvents(): void;

    public function releaseEvents(): array;
}
