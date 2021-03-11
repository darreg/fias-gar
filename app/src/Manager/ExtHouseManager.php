<?php

namespace App\Manager;

use App\DAO\ExtHouseDAO;
use App\Entity\ExtHouse;
use App\Repository\ExtHouseRepository;

class ExtHouseManager
{
    private ExtHouseDAO $extHouseDao;
    private ExtHouseRepository $extHouseRepository;

    public function __construct(
        ExtHouseDAO $extHouseDao,
        ExtHouseRepository $extHouseRepository
    ) {
        $this->extHouseDao = $extHouseDao;
        $this->extHouseRepository = $extHouseRepository;
    }

    public function getOne(int $objectid): ?ExtHouse
    {
        return $this->extHouseRepository->find($objectid);
    }

    /**
     * @return ExtHouse[]
     *
     * @psalm-return array<int, ExtHouse>
     */
    public function getAll(?int $limit = null, ?int $offset = null): array
    {
        return $this->extHouseRepository->findBy([], null, $limit, $offset);
    }

    public function add(
        int $objectid,
        ?string $objectguid = null,
        ?int $precision = null,
        ?float $latitude = null,
        ?float $longitude = null,
        ?int $zoom = null
    ): bool {

        try {
            $this->extHouseDao->create(
                $objectid,
                $objectguid,
                $precision,
                $latitude,
                $longitude,
                $zoom
            );
        } catch (\Exception $e) {
            //TODO log
            return false;
        }

        return true;
    }

    public function updateById(
        int $objectid,
        ?string $objectguid = null,
        ?int $precision = null,
        ?float $latitude = null,
        ?float $longitude = null,
        ?int $zoom = null
    ): bool {

        $extHouse = $this->extHouseRepository->find($objectid);
        if ($extHouse === null) {
            return false;
        }

        return $this->update(
            $extHouse,
            $objectguid,
            $precision,
            $latitude,
            $longitude,
            $zoom
        );
    }

    public function updatePartById(
        int $objectid,
        ?string $objectguid = null,
        ?int $precision = null,
        ?float $latitude = null,
        ?float $longitude = null,
        ?int $zoom = null
    ): bool {

        $extHouse = $this->extHouseRepository->find($objectid);
        if ($extHouse === null) {
            return false;
        }

        $data = [];
        if ($objectguid !== null) {
            $data['objectguid'] = $objectguid;
        }
        if ($precision !== null) {
            $data['precision'] = $precision;
        }
        if ($latitude !== null) {
            $data['latitude'] = $latitude;
        }
        if ($longitude !== null) {
            $data['longitude'] = $longitude;
        }
        if ($zoom !== null) {
            $data['zoom'] = $zoom;
        }

        return $this->updatePart(
            $extHouse,
            $data
        );
    }

    public function update(
        ExtHouse $extHouse,
        ?string $objectguid = null,
        ?int $precision = null,
        ?float $latitude = null,
        ?float $longitude = null,
        ?int $zoom = null
    ): bool {

        $this->extHouseDao->update(
            $extHouse,
            $objectguid,
            $precision,
            $latitude,
            $longitude,
            $zoom
        );

        return true;
    }

    public function updatePart(
        ExtHouse $extHouse,
        array $data
    ): bool {

        $this->extHouseDao->updatePart(
            $extHouse,
            $data
        );

        return true;
    }

    public function deleteById(int $objectid): bool
    {
        $exHouse = $this->extHouseRepository->find($objectid);
        if ($exHouse === null) {
            return false;
        }

        $this->extHouseDao->delete($exHouse);

        return true;
    }
}
