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
            ->setPrepositive($extAddrobjDto->precision)
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
            ->setPrepositive($extAddrobjDto->precision)
            ->setLocative($extAddrobjDto->locative);

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
