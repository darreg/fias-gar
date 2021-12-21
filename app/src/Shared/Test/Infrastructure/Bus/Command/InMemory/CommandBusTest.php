<?php

declare(strict_types=1);

namespace App\Shared\Test\Infrastructure\Bus\Command\InMemory;

use App\Shared\Domain\Bus\Command\CommandInterface;
use App\Shared\Infrastructure\Bus\Command\CommandNotRegisteredException;
use App\Shared\Infrastructure\Bus\Command\InMemory\CommandBus;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class CommandBusTest extends TestCase
{
    private CommandBus $commandBus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->commandBus = new CommandBus([$this->commandHandler()]);
    }

    public function testCommandHandle(): void
    {
        $this->expectException(RuntimeException::class);


        $this->commandBus->dispatch(new TestCommand());
    }

    public function testNonRegisteredCommand(): void
    {
        $this->expectException(CommandNotRegisteredException::class);

        $nonRegisteredCommand = $this->createStub(CommandInterface::class);
        $this->commandBus->dispatch($nonRegisteredCommand);
    }

    private function commandHandler(): object
    {
        return new class {
            public function __invoke(TestCommand $command)
            {
                throw new RuntimeException('This works fine!');
            }
        };
    }
}
