<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Event;

use App\Shared\Domain\Bus\Event\EventSubscriberInterface;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

abstract class AbstractEventSubscriber implements MessageSubscriberInterface, EventSubscriberInterface
{
    public static function subscribeTo(): array
    {
        return [];
    }

    public static function getHandledMessages(): iterable
    {
        foreach(static::subscribeTo() as $className) {
            yield $className => ['bus' => 'event.bus'];
        }
    }
}