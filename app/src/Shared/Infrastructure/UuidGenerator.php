<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure;

use App\Shared\Domain\UuidGeneratorInterface;
use Symfony\Component\Uid\Uuid;

final class UuidGenerator implements UuidGeneratorInterface
{
    public function generate(): string
    {
        return (string)Uuid::v4();
    }
}
