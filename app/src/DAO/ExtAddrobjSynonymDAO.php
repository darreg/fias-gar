<?php

namespace App\DAO;

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
        ExtAddrobjSynonym $extAddrobjSynonym
    ): ExtAddrobjSynonym {

        $this->entityManager->persist($extAddrobjSynonym);
        $this->entityManager->flush();

        return $extAddrobjSynonym;
    }

    public function update(
        ExtAddrobjSynonym $extAddrobjSynonym
    ): ExtAddrobjSynonym {

        $this->entityManager->flush();

        return $extAddrobjSynonym;
    }

    public function delete(ExtAddrobjSynonym $extAddrobjSynonym): void
    {
        $this->entityManager->remove($extAddrobjSynonym);
        $this->entityManager->flush();
    }

    /**
     * @param array<int, ExtAddrobjSynonym> $extAddrobjSynonyms
     */
    public function deleteAll(array $extAddrobjSynonyms): void
    {
        foreach ($extAddrobjSynonyms as $extAddrobjSynonym) {
            $this->entityManager->remove($extAddrobjSynonym);
        }
        $this->entityManager->flush();
    }
}
