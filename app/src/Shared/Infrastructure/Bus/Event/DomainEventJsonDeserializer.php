<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Event;

use App\Shared\Domain\Bus\Event\DomainEvent;
use App\Shared\Domain\Utils;
use JsonException;
use RuntimeException;

final class DomainEventJsonDeserializer
{
    private DomainEventSubscriberLocator $mapping;

    public function __construct(DomainEventSubscriberLocator $mapping)
    {
        $this->mapping = $mapping;
    }

    /**
     * @throws JsonException
     */
    public function deserialize(string $domainEvent): DomainEvent
    {
        $eventData  = Utils::jsonDecode($domainEvent);
        $eventName  = $eventData['data']['type'];
        $eventClass = $this->mapping->for($eventName);

        if ($eventClass === null) {
            throw new RuntimeException("The event <$eventName> doesn't exist or has no subscribers");
        }

        return $eventClass::fromArray(
            $eventData['data']['attributes']['id'],
            $eventData['data']['attributes'],
            $eventData['data']['id'],
            $eventData['data']['date_time']
        );
    }
}
