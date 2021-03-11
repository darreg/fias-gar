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

    public function add(
        int $objectid,
        float $latitude,
        float $longitude
    ): bool {

        $extAddrobj = $this->extAddrobjRepository->find($objectid);
        if ($extAddrobj === null) {
            return false;
        }

        $this->extAddrobjPointDao->create(
            $latitude,
            $longitude,
            $extAddrobj
        );

        return true;
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

        $this->extAddrobjPointDao->update(
            $extAddrobjPoint,
            $latitude,
            $longitude,
            $extAddrobj
        );

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

        $this->extAddrobjPointDao->updateFields(
            $extAddrobjPoint,
            $data,
            $extAddrobj
        );

        return true;
    }

    public function deleteById(int $objectid): bool
    {
        $extAddrobjPoint = $this->extAddrobjPointRepository->find($objectid);
        if ($extAddrobjPoint === null) {
            return false;
        }

        $this->extAddrobjPointDao->delete($extAddrobjPoint);

        return true;
    }
}
