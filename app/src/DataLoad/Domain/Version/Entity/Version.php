<?php

namespace App\DataLoad\Domain\Version\Entity;

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
     * @ORM\Column(type="boolean")
     */
    private bool $hasFullXml;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $hasDeltaXml;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?DateTimeImmutable $fullLoadedAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?DateTimeImmutable $deltaLoadedAt;
    /**
     * @ORM\Column(type="boolean")
     */
    private bool $covered;

    public function __construct(
        string $id,
        string $title,
        DateTimeImmutable $date,
        bool $hasFullXml,
        bool $hasDeltaXml,
        bool $covered = false,
        ?DateTimeImmutable $fullLoadedAt = null,
        ?DateTimeImmutable $deltaLoadedAt = null,
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->date = $date;
        $this->hasFullXml = $hasFullXml;
        $this->hasDeltaXml = $hasDeltaXml;
        $this->covered = $covered;
        $this->fullLoadedAt = $fullLoadedAt;
        $this->deltaLoadedAt = $deltaLoadedAt;
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

    public function hasFullXml(): bool
    {
        return $this->hasFullXml;
    }

    public function hasDeltaXml(): bool
    {
        return $this->hasDeltaXml;
    }

    public function isCovered(): bool
    {
        return $this->covered;
    }

    public function getFullLoadedAt(): ?DateTimeImmutable
    {
        return $this->fullLoadedAt;
    }

    public function setFullLoadedAt(DateTimeImmutable $fullLoadedAt): self
    {
        $this->fullLoadedAt = $fullLoadedAt;

        return $this;
    }

    public function getDeltaLoadedAt(): ?DateTimeImmutable
    {
        return $this->deltaLoadedAt;
    }

    public function setDeltaLoadedAt(DateTimeImmutable $deltaLoadedAt): self
    {
        $this->deltaLoadedAt = $deltaLoadedAt;

        return $this;
    }
}
