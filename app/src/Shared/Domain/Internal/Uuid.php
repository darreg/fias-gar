<?php

declare(strict_types=1);

namespace App\Shared\Domain\Internal;

use Symfony\Component\Uid\Uuid as SymfonyUuid;

class Uuid
{
    public static function generate(): string
    {
        return (string)SymfonyUuid::v4();
    }

    public static function isValid(string $value): bool
    {
        return SymfonyUuid::isValid($value);
    }
}
