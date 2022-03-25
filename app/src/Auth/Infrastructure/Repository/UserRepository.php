<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Repository;

use App\Auth\Domain\User\Entity\User;
use App\Auth\Domain\User\Repository\UserRepositoryInterface;
use App\Shared\Domain\Exception\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class UserRepository implements UserRepositoryInterface
{
    private EntityManagerInterface $em;
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(User::class);
    }

    /**
     * @psalm-suppress MixedReturnStatement
     * @psalm-suppress MixedInferredReturnType
     */
    public function find(string $id): ?User
    {
        return $this->repo->find($id);
    }

    public function findOrFail(string $id): User
    {
        $user = $this->find($id);
        if ($user === null) {
            throw new EntityNotFoundException('User is not found.');
        }

        return $user;
    }

    public function persist(User $user): void
    {
        $this->em->persist($user);
    }

    public function remove(User $user): void
    {
        $this->em->remove($user);
    }
}
