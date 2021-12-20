<?php

declare(strict_types=1);

namespace App\Shared\Test\Infrastructure\Bus\Query\InMemory;

use App\Shared\Domain\Bus\Query\QueryHandlerInterface;
use App\Shared\Domain\Bus\Query\ResponseInterface;

class TestHandler implements QueryHandlerInterface
{
    public function __invoke(TestQuery $query): ?ResponseInterface
    {
        return new TestResponse(42);
    }
}
