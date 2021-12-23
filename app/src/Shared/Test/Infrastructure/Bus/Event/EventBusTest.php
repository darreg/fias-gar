<?php

declare(strict_types=1);

namespace App\Shared\Test\Infrastructure\Bus\Event;

use App\Shared\Domain\Bus\Event\EventInterface;
use App\Shared\Infrastructure\Bus\Event\EventBus;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class EventBusTest extends TestCase
{
    private EventBus $eventBus;

    protected function setUp(): void
    {
        $this->symfonyMessageBus = $this->fakeEventBus();
        $this->eventBus = new EventBus($this->symfonyMessageBus);
    }

    public function testDispatching(): void
    {
        $event = new TestEvent();
        $this->eventBus->publish($event);

        self::assertSame($event, $this->symfonyMessageBus->lastDispatchedEvent());
    }

    private function fakeEventBus(): MessageBusInterface
    {
        return new class () implements MessageBusInterface {
            private EventInterface $dispatchedEvent;

            public function dispatch($message, array $stamps = []): Envelope
            {
                $this->dispatchedEvent = $message;
                return new Envelope($message);
            }

            public function lastDispatchedEvent(): EventInterface
            {
                return $this->dispatchedEvent;
            }
        };
    }
}
