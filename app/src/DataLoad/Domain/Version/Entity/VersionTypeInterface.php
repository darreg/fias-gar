<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Version\Entity;

use DateTimeImmutable;

interface VersionTypeInterface
{
    public function isHasXml(): bool;

    public function getLoadTryNum(): int;

    public function setLoadTryNum(int $loadTryNum): self;

    public function isBrokenUrl(): bool;

    public function setBrokenUrl(bool $brokenUrl): self;

    public function getLoadedAt(): ?DateTimeImmutable;

    public function setLoadedAt(DateTimeImmutable $loadedAt): self;

    public function incrementLoadTryNum(): void;
}
