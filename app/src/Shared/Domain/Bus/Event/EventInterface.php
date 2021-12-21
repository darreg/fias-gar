<?php

declare(strict_types=1);

namespace App\Shared\Domain\Bus\Event;

interface EventInterface
{
    public static function eventName(): string;
}
