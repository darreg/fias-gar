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

        try {
            $this->extAddrobjSynonymDao->create(
                $name,
                $extAddrobj
            );
        } catch (\Exception $e) {
            //TODO log
            return false;
        }

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

        try {
            $this->extAddrobjSynonymDao->update(
                $extAddrobjSynonym,
                $name,
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
     *     name?: string,
     * } $data
     */
    public function updateFields(
        ExtAddrobjSynonym $extAddrobjSynonym,
        array $data
    ): bool {

        $extAddrobj = null;
        if (isset($data['objectid'])) {
            $extAddrobj = $this->extAddrobjRepository->find($data['objectid']);
        }

        try {
            $this->extAddrobjSynonymDao->updateFields(
                $extAddrobjSynonym,
                $data['name'] ?? null,
                $extAddrobj
            );
        } catch (\Exception $e) {
            //TODO log
            return false;
        }

        return true;
    }

    public function deleteById(int $objectid): bool
    {
        $extAddrobjSynonym = $this->extAddrobjSynonymRepository->find($objectid);
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
}
