<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Event;

use App\Shared\Domain\Bus\Event\EventSubscriberInterface;
use RuntimeException;

use function Lambdish\Phunctional\reduce;

final class DomainEventSubscriberLocator
{
    private iterable $subscribers;
    private array $subscribed;

    public function __construct(iterable $subscribers)
    {
        $this->subscribers = $subscribers;
        $this->subscribed = self::getSubscribedByEventClass($subscribers);
    }

    public function getAll(): iterable
    {
        return $this->subscribers;
    }

    public function getSubscribedTo(string $eventClass)
    {
        if (!isset($this->subscribed[$eventClass])) {
            throw new RuntimeException("The Domain Event for <$eventClass> doesn't exists or have no subscribers");
        }

        return $this->subscribed[$eventClass];
    }

    public static function getSubscribedByEventClass(iterable $subscribers): array
    {
        return reduce(
            static function ($subscribers, EventSubscriberInterface $subscriber): array {
                foreach ($subscriber->subscribedTo() as $eventClass) {
                    $subscribers[$eventClass][] = $subscriber;
                }
                return $subscribers;
            },
            $subscribers,
            []
        );
    }

//    public function withRabbitMqQueueNamed(string $queueName): EventSubscriberInterface
//    {
//        $subscriber = search(
//            static fn(EventSubscriberInterface $subscriber) => RabbitMqQueueNameFormatter::format($subscriber) ===
//                $queueName,
//            $this->mapping
//        );
//
//        if ($subscriber === null) {
//            throw new RuntimeException("There are no subscribers for the <$queueName> queue");
//        }
//
//        return $subscriber;
//    }
}
