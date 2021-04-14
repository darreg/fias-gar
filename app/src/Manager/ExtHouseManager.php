<?php

namespace App\Manager;

use App\DAO\ExtHouseDAO;
use App\DTO\ExtHouseDTO;
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

    public function add(ExtHouseDTO $extHouseDTO): ?ExtHouse
    {
        $extHouse = (new ExtHouse())
            ->setObjectid($extHouseDTO->objectid)
            ->setObjectguid($extHouseDTO->objectguid)
            ->setPrecision($extHouseDTO->precision)
            ->setLatitude($extHouseDTO->latitude)
            ->setLongitude($extHouseDTO->longitude)
            ->setZoom($extHouseDTO->zoom);

        try {
            $this->extHouseDao->create($extHouse);
        } catch (\Exception $e) {
            //TODO log
            return null;
        }

        return $extHouse;
    }

    public function updateById(
        int $objectid,
        ExtHouseDTO $extHouseDto
    ): ?ExtHouse {

        $extHouse = $this->extHouseRepository->find($objectid);
        if ($extHouse === null) {
            return null;
        }

        return $this->update(
            $extHouse,
            $extHouseDto
        );
    }

    public function update(
        ExtHouse $extHouse,
        ExtHouseDTO $extHouseDto
    ): ?ExtHouse {

        $extHouse
            ->setObjectguid($extHouseDto->objectguid)
            ->setPrecision($extHouseDto->precision)
            ->setLatitude($extHouseDto->latitude)
            ->setLongitude($extHouseDto->longitude)
            ->setZoom($extHouseDto->zoom);

        try {
            $this->extHouseDao->update($extHouse);
        } catch (\Exception $e) {
            //TODO log
            return null;
        }

        return $extHouse;
    }

    public function deleteById(int $objectid): bool
    {
        $exHouse = $this->extHouseRepository->find($objectid);
        if ($exHouse === null) {
            return false;
        }

        try {
            $this->extHouseDao->delete($exHouse);
        } catch (\Exception $e) {
            //TODO log
            return false;
        }

        return true;
    }
}
