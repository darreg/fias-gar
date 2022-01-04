<?php

declare(strict_types=1);

namespace App\Shared\Test\Infrastructure\Bus\Event;

use App\Shared\Domain\Bus\Event\EventInterface;
use App\Shared\Infrastructure\Bus\Event\EventBus;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\StampInterface;

/**
 * @internal
 */
final class EventBusTest extends TestCase
{
    public function testDispatching(): void
    {
        $symfonyMessageBus = $this->fakeEventBus();
        $eventBus = new EventBus($symfonyMessageBus);

        $event = new TestEvent();
        $eventBus->publish($event);

        /** @psalm-suppress UndefinedInterfaceMethod */
        self::assertSame($event, $symfonyMessageBus->lastDispatchedEvent());
    }

    private function fakeEventBus(): MessageBusInterface
    {
        /** @psalm-suppress MissingConstructor */
        return new class() implements MessageBusInterface {
            private object $dispatchedEvent;

            /** @psalm-suppress MethodSignatureMismatch */
            public function dispatch(object $message, array $stamps = []): Envelope
            {
                $this->dispatchedEvent = $message;
                return new Envelope($message);
            }

            public function lastDispatchedEvent(): object
            {
                return $this->dispatchedEvent;
            }
        };
    }
}
