<?php

declare(strict_types=1);

namespace App\Auth\Domain\User\Repository;

use App\Auth\Domain\Shared\ReadModel\AuthModel;
use App\Auth\Domain\User\Entity\Email;

interface UserFetcherInterface
{
    public function findForAuthByEmail(string $email): AuthModel;

    public function hasByEmail(Email $email): bool;
}
