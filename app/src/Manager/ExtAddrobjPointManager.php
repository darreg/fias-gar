<?php

namespace App\Manager;

use App\DAO\ExtAddrobjPointDAO;
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

    public function add(
        int $objectid,
        float $latitude,
        float $longitude
    ): bool {

        $extAddrobj = $this->extAddrobjRepository->find($objectid);
        if ($extAddrobj === null) {
            return false;
        }

        try {
            $this->extAddrobjPointDao->create(
                $latitude,
                $longitude,
                $extAddrobj
            );
        } catch (\Exception $e) {
            //TODO log
            return false;
        }

        return true;
    }

    public function updateById(
        int $id,
        int $objectid,
        float $latitude,
        float $longitude
    ): bool {

        $extAddrobjPoint = $this->extAddrobjPointRepository->find($id);
        if ($extAddrobjPoint === null) {
            return false;
        }

        return $this->update(
            $extAddrobjPoint,
            $objectid,
            $latitude,
            $longitude
        );
    }

    public function update(
        ExtAddrobjPoint $extAddrobjPoint,
        int $objectid,
        float $latitude,
        float $longitude
    ): bool {

        $extAddrobj = $this->extAddrobjRepository->find($objectid);
        if ($extAddrobj === null) {
            return false;
        }

        try {
            $this->extAddrobjPointDao->update(
                $extAddrobjPoint,
                $latitude,
                $longitude,
                $extAddrobj
            );
        } catch (\Exception $e) {
            //TODO log
            return false;
        }

        return true;
    }

    /**
     * @psalm-param array{
     *     objectid?: int,
     *     latitude?: float,
     *     longitude?: float,
     * } $data
     */
    public function updateFields(
        ExtAddrobjPoint $extAddrobjPoint,
        array $data
    ): bool {

        $extAddrobj = null;
        if (isset($data['objectid'])) {
            $extAddrobj = $this->extAddrobjRepository->find($data['objectid']);
        }

        try {
            $this->extAddrobjPointDao->updateFields(
                $extAddrobjPoint,
                $data,
                $extAddrobj
            );
        } catch (\Exception $e) {
            //TODO log
            return false;
        }

        return true;
    }

    /**
     * @psalm-param array{
     *     objectid?: int,
     *     latitude?: float,
     *     longitude?: float,
     * } $data
     */
    public function updateFieldsById(
        int $id,
        array $data
    ): bool {

        $extAddrobjPoint = $this->extAddrobjPointRepository->find($id);
        if ($extAddrobjPoint === null) {
            return false;
        }

        return $this->updateFields(
            $extAddrobjPoint,
            $data
        );
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
        $extAddrobjPoints = $this->extAddrobjPointRepository->findBy(['objectid' => $objectid]);
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
