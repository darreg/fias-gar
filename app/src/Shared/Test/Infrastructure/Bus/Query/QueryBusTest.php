<?php

declare(strict_types=1);

namespace App\Shared\Test\Infrastructure\Bus\Query;

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
    public function testDispatching(): void
    {
        $symfonyMessageBus = $this->fakeQueryBus();
        $queryBus = new QueryBus($symfonyMessageBus);

        $query = new TestQuery();
        $queryBus->ask($query);

        /** @psalm-suppress UndefinedInterfaceMethod */
        self::assertSame($query, $symfonyMessageBus->lastDispatchedQuery());
    }

    private function fakeQueryBus(): MessageBusInterface
    {
        /** @psalm-suppress MissingConstructor */
        return new class() implements MessageBusInterface {
            private object $dispatchedQuery;

            /** @psalm-suppress MethodSignatureMismatch */
            public function dispatch(object $message, array $stamps = []): Envelope
            {
                $this->dispatchedQuery = $message;
                return (new Envelope($message))->with(new HandledStamp(
                    new TestResponse(42),
                    'test'
                ));
            }

            public function lastDispatchedQuery(): object
            {
                return $this->dispatchedQuery;
            }
        };
    }
}
