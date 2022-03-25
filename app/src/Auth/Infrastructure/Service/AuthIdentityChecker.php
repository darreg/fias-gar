<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Service;

use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthIdentityChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof AuthIdentity) {
            return;
        }

        if (!$user->isActive()) {
            $exception = new DisabledException('Account is disabled.');
            $exception->setUser($user);
            throw $exception;
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof AuthIdentity) {
            return;
        }
    }
}
