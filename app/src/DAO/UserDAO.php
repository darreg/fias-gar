<?php

namespace App\DAO;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserDAO
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(User $User): User
    {
        $this->entityManager->persist($User);
        $this->entityManager->flush();

        return $User;
    }

    public function update(User $User): User
    {
        $this->entityManager->flush();

        return $User;
    }

    public function delete(User $User): void
    {
        $this->entityManager->remove($User);
        $this->entityManager->flush();
    }
}
