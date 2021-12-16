<?php

namespace App\BackOffice\DAO;

use App\BackOffice\Entity\ExtHouse;
use Doctrine\ORM\EntityManagerInterface;

class ExtHouseDAO
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(ExtHouse $extHouse): ExtHouse
    {

        $this->entityManager->persist($extHouse);
        $this->entityManager->flush();

        return $extHouse;
    }

    public function update(ExtHouse $extHouse): ExtHouse
    {

        $this->entityManager->flush();

        return $extHouse;
    }

    public function delete(ExtHouse $extHouse): void
    {
        $this->entityManager->remove($extHouse);
        $this->entityManager->flush();
    }
}
