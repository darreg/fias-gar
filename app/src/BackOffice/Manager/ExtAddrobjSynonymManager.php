<?php

namespace App\BackOffice\Manager;

use App\BackOffice\DAO\ExtAddrobjSynonymDAO;
use App\BackOffice\DTO\ExtAddrobjSynonymDTO;
use App\BackOffice\Entity\ExtAddrobjSynonym;
use App\BackOffice\Repository\ExtAddrobjRepository;
use App\BackOffice\Repository\ExtAddrobjSynonymRepository;

use function App\Manager\count;

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

    public function add(ExtAddrobjSynonymDTO $extAddrobjSynonymDTO): ?ExtAddrobjSynonym
    {

        $extAddrobj = $this->extAddrobjRepository->find($extAddrobjSynonymDTO->objectid);
        if ($extAddrobj === null) {
            return null;
        }

        $extAddrobjSynonym = (new ExtAddrobjSynonym())
            ->setName($extAddrobjSynonymDTO->name)
            ->setExtAddrobj($extAddrobj);

        try {
            $this->extAddrobjSynonymDao->create($extAddrobjSynonym);
        } catch (\Exception $e) {
            //TODO log
            return null;
        }

        return $extAddrobjSynonym;
    }

    public function updateById(
        int $id,
        ExtAddrobjSynonymDTO $extAddrobjSynonymDTO
    ): ?ExtAddrobjSynonym {

        $extAddrobjSynonym = $this->extAddrobjSynonymRepository->find($id);
        if ($extAddrobjSynonym === null) {
            return null;
        }

        return $this->update(
            $extAddrobjSynonym,
            $extAddrobjSynonymDTO
        );
    }

    public function update(
        ExtAddrobjSynonym $extAddrobjSynonym,
        ExtAddrobjSynonymDTO $extAddrobjSynonymDTO
    ): ?ExtAddrobjSynonym {

        $extAddrobj = $this->extAddrobjRepository->find($extAddrobjSynonymDTO->objectid);
        if ($extAddrobj === null) {
            return null;
        }

        $extAddrobjSynonym
            ->setName($extAddrobjSynonymDTO->name)
            ->setExtAddrobj($extAddrobj);

        try {
            $this->extAddrobjSynonymDao->update($extAddrobjSynonym);
        } catch (\Exception $e) {
            //TODO log
            return null;
        }

        return $extAddrobjSynonym;
    }

    public function delete(ExtAddrobjSynonym $extAddrobjSynonym): bool
    {
        $id = $extAddrobjSynonym->getId();
        if ($id === null) {
            return false;
        }

        return $this->deleteById($id);
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
