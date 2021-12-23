<?php

declare(strict_types=1);

namespace App\Shared\Domain\Aggregate;

use App\Shared\Domain\Bus\Event\EventInterface;

trait EventsTrait
{
    private array $recordedEvents = [];

    final protected function recordEvent(EventInterface $event): void
    {
        $this->recordedEvents[] = $event;
    }

    final public function releaseEvents(): array
    {
        $events = $this->recordedEvents;
        $this->recordedEvents = [];

        return $events;
    }
}