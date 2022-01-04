<?php

declare(strict_types=1);

namespace App\Shared\Domain\Bus\Event;

interface EventSubscriberInterface
{
    /**
     * @return list<string>
     */
    public static function subscribedTo(): array;
}
