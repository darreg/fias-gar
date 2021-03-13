<?php

namespace App\Manager;

use App\DAO\ExtAddrobjDAO;
use App\DTO\ExtAddrobjDTO;
use App\Entity\ExtAddrobj;
use App\Repository\ExtAddrobjRepository;

class ExtAddrobjManager
{
    private ExtAddrobjDAO $extAddrobjDao;
    private ExtAddrobjRepository $extAddrobjRepository;

    public function __construct(
        ExtAddrobjDAO $extAddrobjDao,
        ExtAddrobjRepository $extAddrobjRepository
    ) {
        $this->extAddrobjDao = $extAddrobjDao;
        $this->extAddrobjRepository = $extAddrobjRepository;
    }

    public function getOne(int $objectid): ?ExtAddrobj
    {
        return $this->extAddrobjRepository->find($objectid);
    }

    /**
     * @return ExtAddrobj[]
     *
     * @psalm-return array<int, ExtAddrobj>
     */
    public function getAll(?int $limit = null, ?int $offset = null): array
    {
        return $this->extAddrobjRepository->findBy([], null, $limit, $offset);
    }

    public function add(ExtAddrobjDTO $extAddrobjDto): bool
    {

        $extAddrobj = (new ExtAddrobj())
            ->setObjectid($extAddrobjDto->getObjectid())
            ->setObjectguid($extAddrobjDto->getObjectguid())
            ->setPrecision($extAddrobjDto->getPrecision())
            ->setLatitude($extAddrobjDto->getLatitude())
            ->setLongitude($extAddrobjDto->getLongitude())
            ->setZoom($extAddrobjDto->getZoom())
            ->setAlias($extAddrobjDto->getAlias())
            ->setAnglicism($extAddrobjDto->getAnglicism())
            ->setNominative($extAddrobjDto->getNominative())
            ->setGenitive($extAddrobjDto->getGenitive())
            ->setDative($extAddrobjDto->getDative())
            ->setAccusative($extAddrobjDto->getAccusative())
            ->setAblative($extAddrobjDto->getAblative())
            ->setPrepositive($extAddrobjDto->getPrepositive())
            ->setLocative($extAddrobjDto->getLocative());

        try {
            $this->extAddrobjDao->create($extAddrobj);
        } catch (\Exception $e) {
            //TODO log
            return false;
        }

        return true;
    }

    public function updateById(
        int $objectid,
        ExtAddrobjDTO $extAddrobjDto
    ): bool {

        $extAddrobj = $this->extAddrobjRepository->find($objectid);
        if ($extAddrobj === null) {
            return false;
        }

        return $this->update(
            $extAddrobj,
            $extAddrobjDto
        );
    }

    public function update(
        ExtAddrobj $extAddrobj,
        ExtAddrobjDTO $extAddrobjDto
    ): bool {

        $extAddrobj
            ->setObjectguid($extAddrobjDto->getObjectguid())
            ->setPrecision($extAddrobjDto->getPrecision())
            ->setLatitude($extAddrobjDto->getLatitude())
            ->setLongitude($extAddrobjDto->getLongitude())
            ->setZoom($extAddrobjDto->getZoom())
            ->setAlias($extAddrobjDto->getAlias())
            ->setAnglicism($extAddrobjDto->getAnglicism())
            ->setNominative($extAddrobjDto->getNominative())
            ->setGenitive($extAddrobjDto->getGenitive())
            ->setDative($extAddrobjDto->getDative())
            ->setAccusative($extAddrobjDto->getAccusative())
            ->setAblative($extAddrobjDto->getAblative())
            ->setPrepositive($extAddrobjDto->getPrepositive())
            ->setLocative($extAddrobjDto->getLocative());

        try {
            $this->extAddrobjDao->update($extAddrobj);
        } catch (\Exception $e) {
            //TODO log
            return false;
        }

        return true;
    }

    public function deleteById(int $objectid): bool
    {
        $exHouse = $this->extAddrobjRepository->find($objectid);
        if ($exHouse === null) {
            return false;
        }

        try {
            $this->extAddrobjDao->delete($exHouse);
        } catch (\Exception $e) {
            //TODO log
            return false;
        }

        return true;
    }
}
