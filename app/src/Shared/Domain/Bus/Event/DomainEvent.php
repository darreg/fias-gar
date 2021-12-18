<?php

declare(strict_types=1);

namespace App\Shared\Domain\Bus\Event;

use App\Shared\Domain\ValueObject\Uuid;
use DateTimeImmutable;
use DateTimeInterface;

abstract class DomainEvent implements EventInterface
{
    private string $aggregateId;
    private string $eventId;
    private string $dateTime;

    public function __construct(string $aggregateId, string $eventId = null, string $dateTime = null)
    {
        $this->aggregateId = $aggregateId;
        $this->eventId = $eventId ?: Uuid::random()->value();
        $this->dateTime = $dateTime ?: (new DateTimeImmutable)->format(DateTimeInterface::ATOM);
    }

    abstract public static function eventName(): string;

    abstract public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $dateTime
    ): self;

    abstract public function toPrimitives(): array;

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public function dateTime(): string
    {
        return $this->dateTime;
    }
}
