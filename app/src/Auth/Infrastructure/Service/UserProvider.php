<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Service;

use App\Auth\Domain\User\Repository\UserFetcherInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    private UserFetcherInterface $userFetcher;

    public function __construct(UserFetcherInterface $userFetcher)
    {
        $this->userFetcher = $userFetcher;
    }

    public function refreshUser(UserInterface $user): void
    {
    }

    /**
     * @param class-string $class
     */
    public function supportsClass(string $class): bool
    {
        return AuthIdentity::class === $class || is_subclass_of($class, AuthIdentity::class);
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $authModel = $this->userFetcher->findForAuthByEmail($identifier);
        return AuthIdentity::fromAuthModel($authModel);
    }

    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        // TODO: when encoded passwords are in use, this method should:
        // 1. persist the new password in the user storage
        // 2. update the $user object with $user->setPassword($newEncodedPassword);
    }

    public function loadUserByUsername(string $username): UserInterface
    {
        return $this->loadUserByIdentifier($username);
    }
}
