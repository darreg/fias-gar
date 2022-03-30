<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Repository;

use App\Auth\Domain\User\Entity\Email;
use App\Auth\Domain\User\Entity\Id;
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
        /** @psalm-suppress InvalidPropertyAssignmentValue */
        $this->repo = $em->getRepository(User::class);
    }

    /**
     * @psalm-suppress MixedReturnStatement
     * @psalm-suppress MixedInferredReturnType
     */
    public function find(Id $id): ?User
    {
        return $this->repo->find($id->getValue());
    }

    /**
     * @throws EntityNotFoundException
     */
    public function findOrFail(Id $id): User
    {
        $user = $this->find($id);
        if ($user === null) {
            throw new EntityNotFoundException('User is not found.');
        }

        return $user;
    }

    /**
     * @psalm-suppress MixedReturnStatement
     * @psalm-suppress MixedInferredReturnType
     */
    public function findByEmail(Email $email): ?User
    {
        return $this->repo->findOneBy(['email' => $email->getValue()]);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function findByEmailOrFail(Email $email): User
    {
        $user = $this->findByEmail($email);
        if ($user === null) {
            throw new EntityNotFoundException('User is not found.');
        }

        return $user;
    }

    /**
     * @psalm-suppress MixedReturnStatement
     * @psalm-suppress MixedInferredReturnType
     */
    public function findByJoinConfirmToken(string $token): ?User
    {
        return $this->repo->findOneBy(['joinConfirmToken.value' => $token]);
    }

    /**
     * @psalm-suppress MixedReturnStatement
     * @psalm-suppress MixedInferredReturnType
     */
    public function findByPasswordResetToken(string $token): ?User
    {
        return $this->repo->findOneBy(['passwordResetToken.value' => $token]);
    }

    /**
     * @psalm-suppress MixedReturnStatement
     * @psalm-suppress MixedInferredReturnType
     */
    public function findByNewEmailToken(string $token): ?User
    {
        return $this->repo->findOneBy(['newEmailToken.value' => $token]);
    }

    public function hasByEmail(Email $email): bool
    {
        return $this->repo->createQueryBuilder('users')
            ->select('COUNT(id)')
            ->andWhere('email = :email')
            ->setParameter('email', $email->getValue())
            ->getQuery()->getSingleScalarResult() > 0;
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
