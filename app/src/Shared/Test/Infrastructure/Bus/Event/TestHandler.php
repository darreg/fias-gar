<?php

declare(strict_types=1);

namespace App\Shared\Test\Infrastructure\Bus\Event;

use App\Shared\Domain\Bus\Event\EventSubscriberInterface;
use RuntimeException;

class TestHandler implements EventSubscriberInterface
{
    public function __invoke(): void
    {
        throw new RuntimeException('This works fine!');
    }

    public static function getHandledMessages(): iterable
    {
        yield TestEvent::class;
    }

    public static function subscribedTo(): array
    {
    }
}
