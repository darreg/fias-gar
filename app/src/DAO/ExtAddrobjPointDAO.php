<?php

namespace App\DAO;

use App\Entity\ExtAddrobjPoint;
use Doctrine\ORM\EntityManagerInterface;

class ExtAddrobjPointDAO
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(
        ExtAddrobjPoint $extAddrobjPoint
    ): ExtAddrobjPoint {

        $this->entityManager->persist($extAddrobjPoint);
        $this->entityManager->flush();

        return $extAddrobjPoint;
    }

    public function update(
        ExtAddrobjPoint $extAddrobjPoint
    ): ExtAddrobjPoint {

        $this->entityManager->flush();

        return $extAddrobjPoint;
    }

    public function delete(ExtAddrobjPoint $extAddrobjPoint): void
    {
        $this->entityManager->remove($extAddrobjPoint);
        $this->entityManager->flush();
    }

    /**
     * @param array<int, ExtAddrobjPoint> $extAddrobjPoints
     */
    public function deleteAll(array $extAddrobjPoints): void
    {
        foreach ($extAddrobjPoints as $extAddrobjPoint) {
            $this->entityManager->remove($extAddrobjPoint);
        }
        $this->entityManager->flush();
    }
}
