<?php

declare(strict_types=1);

namespace App\Shared\Test\Infrastructure\Bus\Command;

use App\Shared\Domain\Bus\Command\CommandInterface;
use App\Shared\Infrastructure\Bus\Command\CommandBus;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\StampInterface;

/**
 * @internal
 */
final class CommandBusTest extends TestCase
{
    public function testDispatching(): void
    {
        $symfonyMessageBus = $this->fakeCommandBus();
        $commandBus = new CommandBus($symfonyMessageBus);

        $command = new TestCommand();
        $commandBus->dispatch($command);

        /** @psalm-suppress UndefinedInterfaceMethod */
        self::assertSame($command, $symfonyMessageBus->lastDispatchedCommand());
    }

    private function fakeCommandBus(): MessageBusInterface
    {
        /** @psalm-suppress MissingConstructor */
        return new class() implements MessageBusInterface {
            private object $dispatchedCommand;

            /** @psalm-suppress MethodSignatureMismatch */
            public function dispatch(object $message, array $stamps = []): Envelope
            {
                $this->dispatchedCommand = $message;
                return new Envelope($message);
            }

            public function lastDispatchedCommand(): object
            {
                return $this->dispatchedCommand;
            }
        };
    }
}
