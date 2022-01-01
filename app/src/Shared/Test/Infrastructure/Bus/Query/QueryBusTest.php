<?php

declare(strict_types=1);

namespace App\Shared\Test\Infrastructure\Bus\Query;

use App\Shared\Domain\Bus\Query\QueryInterface;
use App\Shared\Infrastructure\Bus\Query\QueryBus;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

/**
 * @internal
 */
final class QueryBusTest extends TestCase
{
    private QueryBus $queryBus;

    protected function setUp(): void
    {
        $this->symfonyMessageBus = $this->fakeQueryBus();
        $this->queryBus = new QueryBus($this->symfonyMessageBus);
    }

    public function testDispatching(): void
    {
        $query = new TestQuery();
        $this->queryBus->ask($query);

        self::assertSame($query, $this->symfonyMessageBus->lastDispatchedQuery());
    }

    private function fakeQueryBus(): MessageBusInterface
    {
        return new class() implements MessageBusInterface {
            private QueryInterface $dispatchedQuery;

            public function dispatch($message, array $stamps = []): Envelope
            {
                $this->dispatchedQuery = $message;
                return (new Envelope($message))->with(new HandledStamp(
                    new TestResponse(42),
                    'test'
                ));
            }

            public function lastDispatchedQuery(): QueryInterface
            {
                return $this->dispatchedQuery;
            }
        };
    }
}
