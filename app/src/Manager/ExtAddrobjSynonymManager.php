<?php

namespace App\Manager;

use App\DAO\ExtAddrobjSynonymDAO;
use App\DTO\ExtAddrobjSynonymDTO;
use App\Entity\ExtAddrobjSynonym;
use App\Repository\ExtAddrobjRepository;
use App\Repository\ExtAddrobjSynonymRepository;

class ExtAddrobjSynonymManager
{
    private ExtAddrobjSynonymDAO $extAddrobjSynonymDao;
    private ExtAddrobjSynonymRepository $extAddrobjSynonymRepository;
    private ExtAddrobjRepository $extAddrobjRepository;

    public function __construct(
        ExtAddrobjSynonymDAO $extAddrobjSynonymDao,
        ExtAddrobjSynonymRepository $extAddrobjSynonymRepository,
        ExtAddrobjRepository $extAddrobjRepository
    ) {
        $this->extAddrobjSynonymDao = $extAddrobjSynonymDao;
        $this->extAddrobjSynonymRepository = $extAddrobjSynonymRepository;
        $this->extAddrobjRepository = $extAddrobjRepository;
    }

    public function getOne(int $id): ?ExtAddrobjSynonym
    {
        return $this->extAddrobjSynonymRepository->find($id);
    }

    /**
     * @return ExtAddrobjSynonym[]
     *
     * @psalm-return array<int, ExtAddrobjSynonym>
     */
    public function getAll(int $objectid): array
    {
        $extAddrobj = $this->extAddrobjRepository->find($objectid);
        if ($extAddrobj === null) {
            return [];
        }

        return $this->extAddrobjSynonymRepository->findBy(['extAddrobj' => $extAddrobj]);
    }

    public function add(ExtAddrobjSynonymDTO $extAddrobjSynonymDTO): bool
    {

        $extAddrobj = $this->extAddrobjRepository->find($extAddrobjSynonymDTO->getObjectid());
        if ($extAddrobj === null) {
            return false;
        }

        $extAddrobjSynonym = (new ExtAddrobjSynonym())
            ->setName($extAddrobjSynonymDTO->getName())
            ->setExtAddrobj($extAddrobj);

        try {
            $this->extAddrobjSynonymDao->create($extAddrobjSynonym);
        } catch (\Exception $e) {
            //TODO log
            return false;
        }

        return true;
    }

    public function updateById(
        int $id,
        ExtAddrobjSynonymDTO $extAddrobjSynonymDTO
    ): bool {

        $extAddrobjSynonym = $this->extAddrobjSynonymRepository->find($id);
        if ($extAddrobjSynonym === null) {
            return false;
        }

        return $this->update(
            $extAddrobjSynonym,
            $extAddrobjSynonymDTO
        );
    }

    public function update(
        ExtAddrobjSynonym $extAddrobjSynonym,
        ExtAddrobjSynonymDTO $extAddrobjSynonymDTO
    ): bool {

        $extAddrobj = $this->extAddrobjRepository->find($extAddrobjSynonymDTO->getObjectid());
        if ($extAddrobj === null) {
            return false;
        }

        $extAddrobjSynonym
            ->setName($extAddrobjSynonymDTO->getName())
            ->setExtAddrobj($extAddrobj);

        try {
            $this->extAddrobjSynonymDao->update($extAddrobjSynonym);
        } catch (\Exception $e) {
            //TODO log
            return false;
        }

        return true;
    }

    public function deleteById(int $id): bool
    {
        $extAddrobjSynonym = $this->extAddrobjSynonymRepository->find($id);
        if ($extAddrobjSynonym === null) {
            return false;
        }

        try {
            $this->extAddrobjSynonymDao->delete($extAddrobjSynonym);
        } catch (\Exception $e) {
            //TODO log
            return false;
        }

        return true;
    }

    public function deleteByObjectId(int $objectid): bool
    {
        $extAddrobj = $this->extAddrobjRepository->find($objectid);

        $extAddrobjSynonyms = $this->extAddrobjSynonymRepository->findBy(['extAddrobj' => $extAddrobj]);
        if (count($extAddrobjSynonyms) === 0) {
            return false;
        }

        try {
            $this->extAddrobjSynonymDao->deleteAll($extAddrobjSynonyms);
        } catch (\Exception $e) {
            //TODO log
            return false;
        }

        return true;
    }
}
