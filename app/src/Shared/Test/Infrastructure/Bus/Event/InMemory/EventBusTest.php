<?php

declare(strict_types=1);

namespace App\Shared\Test\Infrastructure\Bus\Event\InMemory;

use App\Shared\Domain\Bus\Event\EventInterface;
use App\Shared\Infrastructure\Bus\Event\InMemory\EventBus;
use PHPUnit\Framework\TestCase;

class EventBusTest extends TestCase
{
    private EventBus $eventBus;

    private EventInterface $event;

    protected function setUp(): void
    {
        parent::setUp();

        $this->event = $this->createMock(EventInterface::class);
        $this->event->method('eventName')->willReturn('event1');

        $this->eventBus = new EventBus([new TestHandler()]);
    }

    public function testEventPublish(): void
    {
        $this->expectException(\RuntimeException::class);

        $this->eventBus->publish($this->event);
    }
}
