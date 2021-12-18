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
    private ?CommandBus $commandBus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->commandBus = new CommandBus([$this->commandHandler()]);
    }

    /** @test */
    public function it_should_be_able_to_handle_a_command(): void
    {
        $this->expectException(RuntimeException::class);

        $this->commandBus->dispatch(new FakeCommand());
    }

    /** @test */
    public function it_should_raise_an_exception_dispatching_a_non_registered_command(): void
    {
        $this->expectException(CommandNotRegisteredException::class);

        $this->commandBus->dispatch($this->command());
    }

    private function commandHandler(): object
    {
        return new class {
            public function __invoke(FakeCommand $command)
            {
                throw new RuntimeException('This works fine!');
            }
        };
    }

    private function command(): CommandInterface
    {
        return $this->createStub(CommandInterface::class);
    }
}