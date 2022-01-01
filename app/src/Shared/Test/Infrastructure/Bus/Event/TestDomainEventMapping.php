<?php

declare(strict_types=1);

namespace App\Shared\Test\Infrastructure\Bus\Event;

use App\Shared\Domain\Bus\Event\EventInterface;
use App\Shared\Domain\Bus\Event\EventSubscriberInterface;
use App\Shared\Infrastructure\Bus\Event\DomainEventMapping;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @internal
 */
final class TestDomainEventMapping extends TestCase
{
    public const EVENT_NAME = 'test_event';
    private string $eventClass;

    protected function setUp(): void
    {
        parent::setUp();

        $this->eventClass = $this->getEventClass();
    }

    public function testNoSubscribers(): void
    {
        $this->expectException(RuntimeException::class);

        $subscriberLocator = new DomainEventMapping($this->getSubscribers(4, 0));
        $subscriberLocator->for(self::EVENT_NAME);
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
        $event->method('eventName')->willReturn(self::EVENT_NAME);
        return \get_class($event);
    }
}
