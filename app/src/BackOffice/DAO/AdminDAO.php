<?php

namespace App\BackOffice\DAO;

use App\BackOffice\Entity\Admin;
use Doctrine\ORM\EntityManagerInterface;

class AdminDAO
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(Admin $Admin): Admin
    {
        $this->entityManager->persist($Admin);
        $this->entityManager->flush();

        return $Admin;
    }

    public function update(Admin $Admin): Admin
    {
        $this->entityManager->flush();

        return $Admin;
    }

    public function delete(Admin $Admin): void
    {
        $this->entityManager->remove($Admin);
        $this->entityManager->flush();
    }
}
