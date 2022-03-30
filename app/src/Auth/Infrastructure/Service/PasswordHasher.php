<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Service;

use App\Auth\Domain\User\Service\PasswordHasherInterface;
use App\Auth\Infrastructure\Security\AuthIdentity;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordHasher implements PasswordHasherInterface
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function hashPassword(string $plainPassword): string
    {
        return $this->passwordHasher->hashPassword(AuthIdentity::blank(), $plainPassword);
    }

    public function isPasswordValid(string $passwordHash, string $plainPassword): bool
    {
        return $this->passwordHasher->isPasswordValid(AuthIdentity::blankWithPassword($passwordHash), $plainPassword);
    }

    public function needsRehash(string $passwordHash): bool
    {
        return $this->passwordHasher->needsRehash(AuthIdentity::blankWithPassword($passwordHash));
    }
}
