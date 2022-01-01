<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence;

use App\Shared\Domain\Aggregate\AggregateRootInterface;
use App\Shared\Domain\Bus\Event\EventBusInterface;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineFlusher
{
    private EntityManagerInterface $entityManager;
    private EventBusInterface $eventBus;

    public function __construct(
        EntityManagerInterface $entityManager,
        EventBusInterface $eventBus
    ) {
        $this->entityManager = $entityManager;
        $this->eventBus = $eventBus;
    }

    public function flush(AggregateRootInterface ...$roots): void
    {
        $this->entityManager->flush();

        foreach ($roots as $root) {
            $this->eventBus->publish(...$root->releaseEvents());
        }
    }
}
