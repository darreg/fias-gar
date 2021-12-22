<?php

declare(strict_types=1);

namespace App\Shared\Test\Infrastructure\Bus\Event;

use App\Shared\Domain\Bus\Event\EventInterface;

final class TestEvent implements EventInterface
{
    public static function eventName(): string
    {
        return 'test.event';
    }
}
