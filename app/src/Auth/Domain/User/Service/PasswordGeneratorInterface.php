<?php

declare(strict_types=1);

namespace App\Auth\Domain\User\Service;

interface PasswordGeneratorInterface
{
    public function generate(): string;
}
