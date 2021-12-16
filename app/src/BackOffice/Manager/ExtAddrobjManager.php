<?php

namespace App\BackOffice\Manager;

use App\BackOffice\DAO\ExtAddrobjDAO;
use App\BackOffice\DTO\ExtAddrobjDTO;
use App\BackOffice\Entity\ExtAddrobj;
use App\BackOffice\Repository\ExtAddrobjRepository;

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

    public function add(ExtAddrobjDTO $extAddrobjDto): ?ExtAddrobj
    {
        $extAddrobj = (new ExtAddrobj())
            ->setObjectid($extAddrobjDto->objectid)
            ->setObjectguid($extAddrobjDto->objectguid)
            ->setPrecision($extAddrobjDto->precision)
            ->setLatitude($extAddrobjDto->latitude)
            ->setLongitude($extAddrobjDto->longitude)
            ->setZoom($extAddrobjDto->zoom)
            ->setAlias($extAddrobjDto->alias)
            ->setAnglicism($extAddrobjDto->anglicism)
            ->setNominative($extAddrobjDto->nominative)
            ->setGenitive($extAddrobjDto->genitive)
            ->setDative($extAddrobjDto->dative)
            ->setAccusative($extAddrobjDto->accusative)
            ->setAblative($extAddrobjDto->ablative)
            ->setPrepositive($extAddrobjDto->prepositive)
            ->setLocative($extAddrobjDto->locative);

        try {
            $this->extAddrobjDao->create($extAddrobj);
        } catch (\Exception $e) {
            //TODO log
            return null;
        }

        return $extAddrobj;
    }

    public function updateById(
        int $objectid,
        ExtAddrobjDTO $extAddrobjDto
    ): ?ExtAddrobj {

        $extAddrobj = $this->extAddrobjRepository->find($objectid);
        if ($extAddrobj === null) {
            return null;
        }

        return $this->update(
            $extAddrobj,
            $extAddrobjDto
        );
    }

    public function update(
        ExtAddrobj $extAddrobj,
        ExtAddrobjDTO $extAddrobjDto
    ): ?ExtAddrobj {

        $extAddrobj
            ->setObjectguid($extAddrobjDto->objectguid)
            ->setPrecision($extAddrobjDto->precision)
            ->setLatitude($extAddrobjDto->latitude)
            ->setLongitude($extAddrobjDto->longitude)
            ->setZoom($extAddrobjDto->zoom)
            ->setAlias($extAddrobjDto->alias)
            ->setAnglicism($extAddrobjDto->anglicism)
            ->setNominative($extAddrobjDto->nominative)
            ->setGenitive($extAddrobjDto->genitive)
            ->setDative($extAddrobjDto->dative)
            ->setAccusative($extAddrobjDto->accusative)
            ->setAblative($extAddrobjDto->ablative)
            ->setPrepositive($extAddrobjDto->prepositive)
            ->setLocative($extAddrobjDto->locative);

        try {
            $this->extAddrobjDao->update($extAddrobj);
        } catch (\Exception $e) {
            //TODO log
            return null;
        }

        return $extAddrobj;
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
