<?php

declare(strict_types=1);

namespace App\Shared\Test\Infrastructure\Bus\Query\InMemory;

use App\Shared\Domain\Bus\Query\QueryInterface;
use App\Shared\Infrastructure\Bus\Query\InMemory\QueryBus;
use App\Shared\Infrastructure\Bus\Query\QueryNotRegisteredException;
use PHPUnit\Framework\TestCase;

final class QueryBusTest extends TestCase
{
    private QueryBus $queryBus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->queryBus = new QueryBus([new TestHandler()]);
    }

    public function testSuccessfulResponse(): void
    {
        $response = $this->queryBus->ask(new TestQuery());
        $this->assertNotNull($response);
        $this->assertEquals(42, $response->number());
    }

    public function testNonRegisteredQuery(): void
    {
        $this->expectException(QueryNotRegisteredException::class);
        $notRegisteredQuery = $this->createStub(QueryInterface::class);

        $this->queryBus->ask($notRegisteredQuery);
    }
}
