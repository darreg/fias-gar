<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure;

use App\Shared\Domain\UlidGeneratorInterface;
use Symfony\Component\Uid\Ulid;

class UlidGenerator implements UlidGeneratorInterface
{
    public function generate(): string
    {
        return Ulid::generate();
    }
}
