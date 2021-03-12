<?php

namespace App\DAO;

use App\Entity\ExtAddrobj;
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
        float $latitude,
        float $longitude,
        ExtAddrobj $extAddrobj
    ): ExtAddrobjPoint {
        $extAddrobjPoint = (new ExtAddrobjPoint())
            ->setLatitude($latitude)
            ->setLongitude($longitude)
            ->setExtAddrobj($extAddrobj);

        $this->entityManager->persist($extAddrobjPoint);
        $this->entityManager->flush();

        return $extAddrobjPoint;
    }

    public function update(
        ExtAddrobjPoint $extAddrobjPoint,
        float $latitude,
        float $longitude,
        ExtAddrobj $extAddrobj
    ): ExtAddrobjPoint {

        $extAddrobjPoint
            ->setLatitude($latitude)
            ->setLongitude($longitude)
            ->setExtAddrobj($extAddrobj);

        $this->entityManager->flush();

        return $extAddrobjPoint;
    }

    /**
     * @psalm-param array{
     *     latitude?: float,
     *     longitude?: float,
     * } $data
     */
    public function updateFields(
        ExtAddrobjPoint $extAddrobjPoint,
        array $data,
        ?ExtAddrobj $extAddrobj = null
    ): ExtAddrobjPoint {

        if (\array_key_exists('latitude', $data)) {
            $extAddrobjPoint->setLatitude($data['latitude']);
        }

        if (\array_key_exists('longitude', $data)) {
            $extAddrobjPoint->setLongitude($data['longitude']);
        }

        if ($extAddrobj !== null) {
            $extAddrobjPoint->setExtAddrobj($extAddrobj);
        }

        $this->entityManager->flush();

        return $extAddrobjPoint;
    }

    public function delete(ExtAddrobjPoint $extAddrobjPoint): void
    {
        $this->entityManager->remove($extAddrobjPoint);
        $this->entityManager->flush();
    }
}
