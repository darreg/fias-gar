<?php

declare(strict_types=1);

namespace App\Auth\Domain\User\Repository;

use App\Auth\Domain\User\ReadModel\AuthModel;

interface UserFetcherInterface
{
    public function findForAuthByEmail(string $email): AuthModel;
}
