<?php

declare(strict_types=1);

namespace App\Shared\Domain\Bus\Event;

interface EventSubscriberInterface
{
    public static function subscribeTo(): array;
}
