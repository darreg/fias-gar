<?php

namespace App\DAO;

use App\Entity\ExtHouse;
use Doctrine\ORM\EntityManagerInterface;

use function array_key_exists;

class ExtHouseDAO
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(
        int $objectid,
        ?string $objectguid = null,
        ?int $precision = null,
        ?float $latitude = null,
        ?float $longitude = null,
        ?int $zoom = null
    ): ExtHouse {
        $extHouse = (new ExtHouse())
            ->setObjectid($objectid)
            ->setObjectguid($objectguid)
            ->setPrecision($precision)
            ->setLatitude($latitude)
            ->setLongitude($longitude)
            ->setZoom($zoom);

        $this->entityManager->persist($extHouse);
        $this->entityManager->flush();

        return $extHouse;
    }

    public function update(
        ExtHouse $extHouse,
        ?string $objectguid = null,
        ?int $precision = null,
        ?float $latitude = null,
        ?float $longitude = null,
        ?int $zoom = null
    ): ExtHouse {

        $extHouse
            ->setObjectguid($objectguid)
            ->setPrecision($precision)
            ->setLatitude($latitude)
            ->setLongitude($longitude)
            ->setZoom($zoom);

        $this->entityManager->flush();

        return $extHouse;
    }

    public function delete(ExtHouse $extHouse): void
    {
        $this->entityManager->remove($extHouse);
        $this->entityManager->flush();
    }
}
