<?php

namespace App\DAO;

use App\Entity\ExtAddrobj;
use App\Entity\ExtAddrobjSynonym;
use Doctrine\ORM\EntityManagerInterface;

class ExtAddrobjSynonymDAO
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(
        string $name,
        ExtAddrobj $extAddrobj
    ): ExtAddrobjSynonym {
        $extAddrobjSynonym = (new ExtAddrobjSynonym())
            ->setName($name)
            ->setExtAddrobj($extAddrobj);

        $this->entityManager->persist($extAddrobjSynonym);
        $this->entityManager->flush();

        return $extAddrobjSynonym;
    }

    public function update(
        ExtAddrobjSynonym $extAddrobjSynonym,
        string $name,
        ExtAddrobj $extAddrobj
    ): ExtAddrobjSynonym {

        $extAddrobjSynonym
            ->setName($name)
            ->setExtAddrobj($extAddrobj);

        $this->entityManager->flush();

        return $extAddrobjSynonym;
    }

    public function delete(ExtAddrobjSynonym $extAddrobjSynonym): void
    {
        $this->entityManager->remove($extAddrobjSynonym);
        $this->entityManager->flush();
    }
}
