<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Service;

use App\Auth\Domain\User\Service\PasswordGeneratorInterface;
use App\Shared\Domain\Internal\Uuid;

class PasswordGenerator implements PasswordGeneratorInterface
{
    public function generate(): string
    {
        return Uuid::generate();
    }
}
