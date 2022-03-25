<?php

declare(strict_types=1);

namespace App\Auth\Domain\Shared\Service;

interface PasswordGeneratorInterface
{
    public function generate(): string;
}
