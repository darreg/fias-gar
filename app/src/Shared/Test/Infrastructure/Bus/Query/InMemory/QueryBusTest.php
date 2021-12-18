<?php

declare(strict_types=1);

namespace App\Shared\Test\Infrastructure\Bus\Query\InMemory;

use App\Shared\Domain\Bus\Query\QueryInterface;
use App\Shared\Infrastructure\Bus\Query\InMemory\QueryBus;
use App\Shared\Infrastructure\Bus\Query\QueryNotRegisteredException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class QueryBusTest extends TestCase
{
    private ?QueryBus $queryBus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->queryBus = new QueryBus([$this->queryHandler()]);
    }

    /** @test */
    public function it_should_return_a_response_successfully(): void
    {
        $this->expectException(RuntimeException::class);

        $this->queryBus->ask(new FakeQuery());
    }

    /** @test */
    public function it_should_raise_an_exception_dispatching_a_non_registered_query(): void
    {
        $this->expectException(QueryNotRegisteredException::class);

        $this->queryBus->ask($this->query());
    }

    private function queryHandler(): object
    {
        return new class {
            public function __invoke(FakeQuery $query)
            {
                throw new RuntimeException('This works fine!');
            }
        };
    }

    private function query(): QueryInterface
    {
        return $this->createStub(QueryInterface::class);
    }
}
