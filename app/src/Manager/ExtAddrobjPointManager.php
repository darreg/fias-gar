<?php

namespace App\Manager;

use App\DAO\ExtAddrobjPointDAO;
use App\DTO\ExtAddrobjPointDTO;
use App\Entity\ExtAddrobjPoint;
use App\Repository\ExtAddrobjPointRepository;
use App\Repository\ExtAddrobjRepository;

class ExtAddrobjPointManager
{
    private ExtAddrobjPointDAO $extAddrobjPointDao;
    private ExtAddrobjPointRepository $extAddrobjPointRepository;
    private ExtAddrobjRepository $extAddrobjRepository;

    public function __construct(
        ExtAddrobjPointDAO $extAddrobjPointDao,
        ExtAddrobjPointRepository $extAddrobjPointRepository,
        ExtAddrobjRepository $extAddrobjRepository
    ) {
        $this->extAddrobjPointDao = $extAddrobjPointDao;
        $this->extAddrobjPointRepository = $extAddrobjPointRepository;
        $this->extAddrobjRepository = $extAddrobjRepository;
    }

    public function getOne(int $id): ?ExtAddrobjPoint
    {
        return $this->extAddrobjPointRepository->find($id);
    }

    /**
     * @return ExtAddrobjPoint[]
     *
     * @psalm-return array<int, ExtAddrobjPoint>
     */
    public function getAll(int $objectid): array
    {
        $extAddrobj = $this->extAddrobjRepository->find($objectid);
        if ($extAddrobj === null) {
            return [];
        }

        return $this->extAddrobjPointRepository->findBy(['extAddrobj' => $extAddrobj]);
    }

    public function add(ExtAddrobjPointDTO $extAddrobjPointDTO): ?ExtAddrobjPoint
    {

        $extAddrobj = $this->extAddrobjRepository->find($extAddrobjPointDTO->objectid);
        if ($extAddrobj === null) {
            return null;
        }

        $extAddrobjPoint = (new ExtAddrobjPoint())
            ->setLatitude($extAddrobjPointDTO->latitude)
            ->setLongitude($extAddrobjPointDTO->longitude)
            ->setExtAddrobj($extAddrobj);

        try {
            $this->extAddrobjPointDao->create($extAddrobjPoint);
        } catch (\Exception $e) {
            //TODO log
            return null;
        }

        return $extAddrobjPoint;
    }

    public function updateById(
        int $id,
        ExtAddrobjPointDTO $extAddrobjPointDTO
    ): ?ExtAddrobjPoint {

        $extAddrobjPoint = $this->extAddrobjPointRepository->find($id);
        if ($extAddrobjPoint === null) {
            return null;
        }

        return $this->update(
            $extAddrobjPoint,
            $extAddrobjPointDTO
        );
    }

    public function update(
        ExtAddrobjPoint $extAddrobjPoint,
        ExtAddrobjPointDTO $extAddrobjPointDTO
    ): ?ExtAddrobjPoint {

        $extAddrobj = $this->extAddrobjRepository->find($extAddrobjPointDTO->objectid);
        if ($extAddrobj === null) {
            return null;
        }

        $extAddrobjPoint
            ->setLatitude($extAddrobjPointDTO->latitude)
            ->setLongitude($extAddrobjPointDTO->longitude)
            ->setExtAddrobj($extAddrobj);

        try {
            $this->extAddrobjPointDao->update(
                $extAddrobjPoint
            );
        } catch (\Exception $e) {
            //TODO log
            return null;
        }

        return $extAddrobjPoint;
    }

    public function deleteById(int $id): bool
    {
        $extAddrobjPoint = $this->extAddrobjPointRepository->find($id);
        if ($extAddrobjPoint === null) {
            return false;
        }

        try {
            $this->extAddrobjPointDao->delete($extAddrobjPoint);
        } catch (\Exception $e) {
            //TODO log
            return false;
        }

        return true;
    }

    public function deleteByObjectId(int $objectid): bool
    {
        $extAddrobj = $this->extAddrobjRepository->find($objectid);

        $extAddrobjPoints = $this->extAddrobjPointRepository->findBy(['extAddrobj' => $extAddrobj]);
        if (count($extAddrobjPoints) === 0) {
            return false;
        }

        try {
            $this->extAddrobjPointDao->deleteAll($extAddrobjPoints);
        } catch (\Exception $e) {
            //TODO log
            return false;
        }

        return true;
    }
}
