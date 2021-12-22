<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\EventForOtherCommand;

use App\Shared\Domain\Bus\Event\EventInterface;

class Event2 implements EventInterface
{
    private string $id;
    private string $name;

    public function __construct(string $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public static function eventName(): string
    {
        return 'event2.for.other.command';
    }
}
