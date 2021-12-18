<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Event;

use App\Shared\Domain\Bus\Event\DomainEvent;
use JsonException;

final class DomainEventJsonSerializer
{
    /**
     * @throws JsonException
     */
    public static function serialize(DomainEvent $domainEvent): string
    {
        return json_encode([
            'data' => [
                'id' => $domainEvent->eventId(),
                'type' => $domainEvent::eventName(),
                'date_time' => $domainEvent->dateTime(),
                'attributes' => array_merge($domainEvent->toPrimitives(), ['id' => $domainEvent->aggregateId()]),
            ],
            'meta' => [],
        ], JSON_THROW_ON_ERROR);
    }
}
