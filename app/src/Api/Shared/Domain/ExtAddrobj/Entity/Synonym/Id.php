<?php

declare(strict_types=1);

namespace App\Api\Shared\Domain\ExtAddrobj\Entity\Synonym;

use App\Shared\Domain\ValueObject\UuidValueObject;

/** @psalm-suppress MissingConstructor */
final class Id extends UuidValueObject
{
    public static function next(): self
    {
        return new self(parent::randomUuid());
    }
}
