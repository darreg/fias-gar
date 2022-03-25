<?php

declare(strict_types=1);

namespace App\Auth\Domain\Shared\Service;

use App\Auth\Domain\Shared\ReadModel\AuthModel;

interface PasswordHasherInterface
{
    public function hashPassword(AuthModel $user, string $plainPassword): string;

    public function isPasswordValid(AuthModel $user, string $plainPassword): bool;

    public function needsRehash(AuthModel $user): bool;
}
