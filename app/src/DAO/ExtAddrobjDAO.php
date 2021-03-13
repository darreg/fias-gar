<?php

namespace App\DAO;

use App\Entity\ExtAddrobj;
use Doctrine\ORM\EntityManagerInterface;

class ExtAddrobjDAO
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(ExtAddrobj $extAddrobj): ExtAddrobj
    {

        $this->entityManager->persist($extAddrobj);
        $this->entityManager->flush();

        return $extAddrobj;
    }

    public function update(ExtAddrobj $extAddrobj): ExtAddrobj
    {

        $this->entityManager->flush();

        return $extAddrobj;
    }

    public function delete(ExtAddrobj $extAddrobj): void
    {
        $this->entityManager->remove($extAddrobj);
        $this->entityManager->flush();
    }
}
