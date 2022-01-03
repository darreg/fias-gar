<?php

declare(strict_types=1);

namespace App\Shared\Domain\Bus\Event;

use App\Shared\Domain\ValueObject\UuidValueObject;
use DateTimeImmutable;
use DateTimeInterface;

abstract class DomainEvent implements EventInterface
{
    private string $aggregateId;
    private string $eventId;
    private string $occurredOn;

    public function __construct(string $aggregateId, string $eventId = null, string $occurredOn = null)
    {
        $this->aggregateId = $aggregateId;
        $this->eventId = $eventId ?: UuidValueObject::next()->getValue();
        $this->occurredOn = $occurredOn ?: (new DateTimeImmutable())->format(DateTimeInterface::ATOM);
    }

    abstract public static function eventName(): string;

    abstract public static function fromArray(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): self;

    abstract public function toArray(): array;

    final public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    final public function eventId(): string
    {
        return $this->eventId;
    }

    final public function occurredOn(): string
    {
        return $this->occurredOn;
    }
}
