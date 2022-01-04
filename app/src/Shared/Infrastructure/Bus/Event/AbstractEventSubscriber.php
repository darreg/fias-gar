<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Event;

use App\Shared\Domain\Bus\Event\EventSubscriberInterface;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

abstract class AbstractEventSubscriber implements MessageSubscriberInterface, EventSubscriberInterface
{
    /**
     * @return list<string>
     */
    final public static function subscribedTo(): array
    {
        return [];
    }

    final public static function getHandledMessages(): iterable
    {
        foreach (static::subscribedTo() as $className) {
            yield $className => ['bus' => 'event.bus'];
        }
    }
}
