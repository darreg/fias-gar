<?php

declare(strict_types=1);

namespace App\Shared\Test\Infrastructure\Bus\Event;

use App\Shared\Domain\Bus\Event\EventInterface;
use App\Shared\Domain\Bus\Event\EventSubscriberInterface;
use App\Shared\Infrastructure\Bus\Event\DomainEventSubscriberLocator;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @internal
 */
final class TestDomainEventSubscriberLocator extends TestCase
{
    private string $eventClass;

    protected function setUp(): void
    {
        parent::setUp();

        $this->eventClass = $this->getEventClass();
    }

    public function testAll(): void
    {
        $subscriberLocator = new DomainEventSubscriberLocator($this->getSubscribers(5, 3));
        $response = $subscriberLocator->getAll();
        self::assertCount(5, $response);
    }

    public function testSubscribedTo(): void
    {
        $subscriberLocator = new DomainEventSubscriberLocator($this->getSubscribers(5, 3));
        $response = $subscriberLocator->getSubscribedTo($this->eventClass);
        self::assertCount(3, $response);
    }

    public function testNoSubscribers(): void
    {
        $this->expectException(RuntimeException::class);

        $subscriberLocator = new DomainEventSubscriberLocator($this->getSubscribers(4, 0));
        $subscriberLocator->getSubscribedTo($this->eventClass);
    }

    private function getSubscribers(int $num, int $subscribedNum): array
    {
        $result = [];
        for ($i = 0; $i < $num; ++$i) {
            $result[$i] = $this->createMock(EventSubscriberInterface::class);
        }

        for ($i = 0; $i < $subscribedNum; ++$i) {
            $result[$i]->method('subscribedTo')->willReturn([$this->eventClass]);
        }

        return $result;
    }

    private function getEventClass(): string
    {
        $event = $this->createMock(EventInterface::class);
        $event->method('eventName')->willReturn('event1');
        return \get_class($event);
    }
}
