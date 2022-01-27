<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Version\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Delta implements VersionTypeInterface
{
    /**
     * @ORM\Column(type="boolean")
     */
    private bool $hasXml;
    /**
     * @ORM\Column(type="integer")
     */
    private int $loadTryNum;
    /**
     * @ORM\Column(type="boolean")
     */
    private bool $brokenUrl;
    /**
     * @ORM\Column(type="datetime_immutable", nullable=true, options={"default" : null})
     */
    private ?DateTimeImmutable $loadedAt;

    public function __construct(
        bool $hasXml,
        int $loadTryNum = 0,
        bool $brokenUrl = false,
        ?DateTimeImmutable $loadedAt = null
    ) {
        $this->hasXml = $hasXml;
        $this->loadTryNum = $loadTryNum;
        $this->brokenUrl = $brokenUrl;
        $this->loadedAt = $loadedAt;
    }

    public function isHasXml(): bool
    {
        return $this->hasXml;
    }

    public function getLoadTryNum(): int
    {
        return $this->loadTryNum;
    }

    public function setLoadTryNum(int $loadTryNum): self
    {
        $this->loadTryNum = $loadTryNum;
        return $this;
    }

    public function isBrokenUrl(): bool
    {
        return $this->brokenUrl;
    }

    public function setBrokenUrl(bool $brokenUrl): self
    {
        $this->brokenUrl = $brokenUrl;
        return $this;
    }

    public function getLoadedAt(): ?DateTimeImmutable
    {
        return $this->loadedAt;
    }

    public function setLoadedAt(DateTimeImmutable $loadedAt): self
    {
        $this->loadedAt = $loadedAt;

        return $this;
    }

    public function incrementLoadTryNum(): void
    {
        ++$this->loadTryNum;
    }
}
