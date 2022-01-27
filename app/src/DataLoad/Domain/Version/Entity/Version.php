<?php

namespace App\DataLoad\Domain\Version\Entity;

use App\DataLoad\Domain\Version\Exception\InvalidVersionTypeException;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="version")
 * @ORM\Entity
 */
class Version
{
    public const TYPE_FULL = 'full';
    public const TYPE_DELTA = 'delta';

    /**
     * @ORM\Id()
     * @ORM\Column(type="string")
     */
    private string $id;

    /**
     * @ORM\Column(type="string")
     */
    private string $title;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $date;

    /**
     * @ORM\Embedded(class=Full::class)
     */
    private Full $full;

    /**
     * @ORM\Embedded(class=Delta::class)
     */
    private Delta $delta;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private bool $covered;

    public function __construct(
        string $id,
        string $title,
        DateTimeImmutable $date,
        Full $full,
        Delta $delta,
        bool $covered = false
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->date = $date;
        $this->full = $full;
        $this->delta = $delta;
        $this->covered = $covered;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getFull(): Full
    {
        return $this->full;
    }

    public function getDelta(): Delta
    {
        return $this->delta;
    }

    public function isCovered(): bool
    {
        return $this->covered;
    }

    /**
     * @param Version::TYPE_* $type
     */
    public function getVersionType(string $type): VersionTypeInterface
    {
        return match ($type) {
            self::TYPE_FULL => $this->getFull(),
            self::TYPE_DELTA => $this->getDelta(),
            default => throw new InvalidVersionTypeException("Invalid version type '{$type}'"),
        };
    }
}
