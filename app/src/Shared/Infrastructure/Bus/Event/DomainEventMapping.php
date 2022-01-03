<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Event;

use App\Shared\Domain\Bus\Event\EventSubscriberInterface;
use LogicException;
use RuntimeException;
use function Lambdish\Phunctional\reduce;
use function Lambdish\Phunctional\reindex;

final class DomainEventMapping
{
    private mixed $mapping;

    public function __construct(iterable $subscribers)
    {
        $this->mapping = reduce(self::eventsExtractor(), $subscribers, []);
    }

    public function for(string $eventName)
    {
        if (!isset($this->mapping[$eventName])) {
            throw new RuntimeException("The Domain Event Class for <{$eventName}> doesn't exists or have no subscribers");
        }

        return $this->mapping[$eventName];
    }

    /** @throws LogicException */
    private static function eventsExtractor(): callable
    {
        return static fn (array $subscribers, EventSubscriberInterface $subscriber) => array_merge(
            $subscribers,
            reindex(
                self::eventNameExtractor(),
                self::getHandledMessages($subscriber)
            )
        );
    }

    private static function eventNameExtractor(): callable
    {
        return static function (string $eventClass): string {
            if (!method_exists($eventClass, 'eventName')) {
                throw new LogicException("The Domain Event Class for <{$eventClass}> have no method 'eventName'");
            }
            return $eventClass::eventName();
        };
    }

    private static function getHandledMessages(EventSubscriberInterface $subscriber): array
    {
        $handledMessages = [];
        foreach ($subscriber->getHandledMessages() as $handledMessage) {
            $handledMessages[] = \is_array($handledMessage) ? key($handledMessage) : $handledMessage;
        }

        return $handledMessages;
    }
}
