<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\EventForOtherCommand;

use App\Shared\Domain\Bus\Event\EventInterface;
use App\Shared\Infrastructure\Bus\Event\AbstractEventSubscriber;

class Subscriber extends AbstractEventSubscriber
{
    public function __invoke(EventInterface $event): void
    {
        dump(['EventForOtherCommand', $event->eventName()]);
    }

    public static function subscribedTo(): array
    {
        return [Event::class, Event2::class];
    }
}
