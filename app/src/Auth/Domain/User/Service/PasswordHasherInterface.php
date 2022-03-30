<?php

declare(strict_types=1);

namespace App\Auth\Domain\User\Service;

interface PasswordHasherInterface
{
    public function hashPassword(string $plainPassword): string;

    public function isPasswordValid(string $passwordHash, string $plainPassword): bool;

    public function needsRehash(string $passwordHash): bool;
}
