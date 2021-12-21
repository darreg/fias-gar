<?php

declare(strict_types=1);

namespace App\Shared\Domain\Bus\Event;

interface EventSubscriberInterface
{
    /** @return array<int, class-string> */
    public function subscribedTo(): array;
}
