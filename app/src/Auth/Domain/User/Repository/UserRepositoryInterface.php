<?php

declare(strict_types=1);

namespace App\Auth\Domain\User\Repository;

use App\Auth\Domain\User\Entity\Email;
use App\Auth\Domain\User\Entity\Id;
use App\Auth\Domain\User\Entity\User;
use App\Shared\Domain\Exception\EntityNotFoundException;

interface UserRepositoryInterface
{
    public function find(Id $id): ?User;

    /**
     * @throws EntityNotFoundException
     */
    public function findOrFail(Id $id): User;

    public function findByEmail(Email $email): ?User;

    /**
     * @throws EntityNotFoundException
     */
    public function findByEmailOrFail(Email $email): User;

    public function findByJoinConfirmToken(string $token): ?User;

    public function findByPasswordResetToken(string $token): ?User;

    public function findByEmailChangeToken(string $token): ?User;

    public function hasByEmail(Email $email): bool;

    public function persist(User $user): void;

    public function remove(User $user): void;
}
