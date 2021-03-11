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

    /**
     * @psalm-param array{
     *     objectguid?: string,
     *     precision?: int,
     *     latitude?: float,
     *     longitude?: float,
     *     zoom?: int
     * } $data
     */
    public function updateFields(
        ExtHouse $extHouse,
        array $data
    ): ExtHouse {

        if (array_key_exists('objectguid', $data)) {
            $extHouse->setObjectguid($data['objectguid']);
        }

        if (array_key_exists('precision', $data)) {
            $extHouse->setPrecision($data['precision']);
        }

        if (array_key_exists('latitude', $data)) {
            $extHouse->setLatitude($data['latitude']);
        }

        if (array_key_exists('longitude', $data)) {
            $extHouse->setLongitude($data['longitude']);
        }

        if (array_key_exists('zoom', $data)) {
            $extHouse->setZoom($data['zoom']);
        }

        $this->entityManager->flush();

        return $extHouse;
    }

    public function delete(ExtHouse $extHouse): void
    {
        $this->entityManager->remove($extHouse);
        $this->entityManager->flush();
    }
}
