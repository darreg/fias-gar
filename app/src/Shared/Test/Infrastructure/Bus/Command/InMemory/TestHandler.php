<?php

declare(strict_types=1);

namespace App\Shared\Test\Infrastructure\Bus\Command\InMemory;

use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\Bus\Query\ResponseInterface;
use RuntimeException;

class TestHandler implements CommandHandlerInterface
{
    public function __invoke(TestCommand $query): ?ResponseInterface
    {
        throw new RuntimeException('This works fine!');
    }
}
