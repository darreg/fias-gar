<?php

namespace App\Manager;

use App\DAO\ExtAddrobjSynonymDAO;
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

    public function add(
        int $objectid,
        string $name
    ): bool {

        $extAddrobj = $this->extAddrobjRepository->find($objectid);
        if ($extAddrobj === null) {
            return false;
        }

        $this->extAddrobjSynonymDao->create(
            $name,
            $extAddrobj
        );

        return true;
    }

    public function update(
        ExtAddrobjSynonym $extAddrobjSynonym,
        int $objectid,
        string $name
    ): bool {

        $extAddrobj = $this->extAddrobjRepository->find($objectid);
        if ($extAddrobj === null) {
            return false;
        }

        $this->extAddrobjSynonymDao->update(
            $extAddrobjSynonym,
            $name,
            $extAddrobj
        );

        return true;
    }

    public function deleteById(int $objectid): bool
    {
        $extAddrobjSynonym = $this->extAddrobjSynonymRepository->find($objectid);
        if ($extAddrobjSynonym === null) {
            return false;
        }

        $this->extAddrobjSynonymDao->delete($extAddrobjSynonym);

        return true;
    }
}
