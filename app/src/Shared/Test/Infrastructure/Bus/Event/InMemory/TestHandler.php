<?php

declare(strict_types=1);

namespace App\Shared\Test\Infrastructure\Bus\Event\InMemory;

use App\Shared\Domain\Bus\Event\EventInterface;
use App\Shared\Domain\Bus\Event\EventSubscriberInterface;
use RuntimeException;

class TestHandler implements EventSubscriberInterface
{
    public function __invoke(): void
    {
        throw new RuntimeException('This works fine!');
    }

    public function subscribedTo(): array
    {
        return [EventInterface::class];
    }
}
