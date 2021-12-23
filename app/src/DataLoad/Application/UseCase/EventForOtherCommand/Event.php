<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\EventForOtherCommand;

use App\Shared\Domain\Bus\Event\DomainEvent;

class Event extends DomainEvent
{
    private string $type;
    private string $title;
    private string $url;
    private string $courseId;

    public function __construct(
        string $id,
        string $type,
        string $title,
        string $url,
        string $courseId,
        string $eventId = null,
        string $dateTime = null
    ) {
        parent::__construct($id, $eventId, $dateTime);
        $this->type = $type;
        $this->title = $title;
        $this->url = $url;
        $this->courseId = $courseId;
    }

    public static function fromArray(
        string $aggregateId,
        array $body,
        string $eventId,
        string $dateTime
    ): self {
        return new self(
            $aggregateId,
            $body['type'],
            $body['title'],
            $body['url'],
            $body['course_id'],
            $eventId,
            $dateTime
        );
    }

    public function toArray(): array
    {
        return [
            'type'      => $this->type,
            'title'     => $this->title,
            'url'       => $this->url,
            'course_id' => $this->courseId,
        ];
    }

    public static function eventName(): string
    {
        return 'event.for.other.command';
    }
}
