<?php

declare(strict_types=1);

namespace App\Shared\Domain\Aggregate;

use App\Shared\Domain\Bus\Event\EventInterface;

interface AggregateRootInterface
{
    public function clearEvents(): void;

    /**
     * @return list<EventInterface>
     */
    public function releaseEvents(): array;
}
