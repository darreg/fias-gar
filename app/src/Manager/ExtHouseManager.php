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

    public function add(ExtHouseDTO $extHouseDTO): bool
    {

        $extHouse = (new ExtHouse())
            ->setObjectid($extHouseDTO->getObjectid())
            ->setObjectguid($extHouseDTO->getObjectguid())
            ->setPrecision($extHouseDTO->getPrecision())
            ->setLatitude($extHouseDTO->getLatitude())
            ->setLongitude($extHouseDTO->getLongitude())
            ->setZoom($extHouseDTO->getZoom());

        try {
            $this->extHouseDao->create($extHouse);
        } catch (\Exception $e) {
            //TODO log
            return false;
        }

        return true;
    }

    public function updateById(
        int $objectid,
        ExtHouseDTO $extHouseDto
    ): bool {

        $extHouse = $this->extHouseRepository->find($objectid);
        if ($extHouse === null) {
            return false;
        }

        return $this->update(
            $extHouse,
            $extHouseDto
        );
    }

    public function update(
        ExtHouse $extHouse,
        ExtHouseDTO $extHouseDto
    ): bool {

        $extHouse
            ->setObjectguid($extHouseDto->getObjectguid())
            ->setPrecision($extHouseDto->getPrecision())
            ->setLatitude($extHouseDto->getLatitude())
            ->setLongitude($extHouseDto->getLongitude())
            ->setZoom($extHouseDto->getZoom());

        try {
            $this->extHouseDao->update($extHouse);
        } catch (\Exception $e) {
            //TODO log
            return false;
        }

        return true;
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
