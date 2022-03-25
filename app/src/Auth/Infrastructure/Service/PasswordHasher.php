<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Service;

use App\Auth\Domain\Shared\ReadModel\AuthModel;
use App\Auth\Domain\Shared\Service\PasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordHasher implements PasswordHasherInterface
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function hashPassword(AuthModel $user, string $plainPassword): string
    {
        return $this->passwordHasher->hashPassword(AuthIdentity::fromAuthModel($user), $plainPassword);
    }

    public function isPasswordValid(AuthModel $user, string $plainPassword): bool
    {
        return $this->passwordHasher->isPasswordValid(AuthIdentity::fromAuthModel($user), $plainPassword);
    }

    public function needsRehash(AuthModel $user): bool
    {
        return $this->passwordHasher->needsRehash(AuthIdentity::fromAuthModel($user));
    }
}
