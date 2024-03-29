<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Security;

use App\Auth\Domain\User\Repository\UserFetcherInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class AuthIdentityProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    private UserFetcherInterface $userFetcher;

    public function __construct(UserFetcherInterface $userFetcher)
    {
        $this->userFetcher = $userFetcher;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof AuthIdentity) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_debug_type($user)));
        }

        $authModel = $this->userFetcher->findForAuthByEmail($user->getUserIdentifier());
        return AuthIdentity::fromAuthModel($authModel);
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
