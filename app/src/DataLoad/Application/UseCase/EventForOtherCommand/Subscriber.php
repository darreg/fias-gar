<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\EventForOtherCommand;

use App\Shared\Domain\Bus\Event\EventInterface;
use App\Shared\Domain\Bus\Event\EventSubscriberInterface;

class Subscriber implements EventSubscriberInterface
{
    public function __invoke(EventInterface $event): void
    {
        dump($event->eventName());
    }

    public function subscribedTo(): array
    {
        return [Event::class];
    }
}
