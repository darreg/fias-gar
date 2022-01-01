<?php

declare(strict_types=1);

namespace App\Shared\Test\Infrastructure\Bus\Command;

use App\Shared\Domain\Bus\Command\CommandInterface;
use App\Shared\Infrastructure\Bus\Command\CommandBus;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @internal
 */
final class CommandBusTest extends TestCase
{
    private CommandBus $commandBus;

    protected function setUp(): void
    {
        $this->symfonyMessageBus = $this->fakeCommandBus();
        $this->commandBus = new CommandBus($this->symfonyMessageBus);
    }

    public function testDispatching(): void
    {
        $command = new TestCommand();
        $this->commandBus->dispatch($command);

        self::assertSame($command, $this->symfonyMessageBus->lastDispatchedCommand());
    }

    private function fakeCommandBus(): MessageBusInterface
    {
        return new class() implements MessageBusInterface {
            private CommandInterface $dispatchedCommand;

            public function dispatch($message, array $stamps = []): Envelope
            {
                $this->dispatchedCommand = $message;
                return new Envelope($message);
            }

            public function lastDispatchedCommand(): CommandInterface
            {
                return $this->dispatchedCommand;
            }
        };
    }
}
