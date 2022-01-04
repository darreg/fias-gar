<?php

declare(strict_types=1);

namespace App\Api\Shared\Domain\Entity\ExtAddrobj\Synonym;

use App\Shared\Domain\ValueObject\UuidValueObject;

final class Id extends UuidValueObject
{
    public static function next(): self
    {
        return new self(parent::randomUuid());
    }
}
