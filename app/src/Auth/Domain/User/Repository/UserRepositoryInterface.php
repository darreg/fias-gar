<?php

declare(strict_types=1);

namespace App\Auth\Domain\User\Repository;

use App\Auth\Domain\User\Entity\User;
use App\Shared\Domain\Exception\EntityNotFoundException;

interface UserRepositoryInterface
{
    public function find(string $id): ?User;

    /**
     * @throws EntityNotFoundException
     */
    public function findOrFail(string $id): User;

    public function persist(User $user): void;

    public function remove(User $user): void;
}
