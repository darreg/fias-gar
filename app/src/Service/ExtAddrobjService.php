<?php

namespace App\Service;

use App\DAO\ExtAddrobjPointDAO;
use App\DTO\ExtAddrobjDTO;
use App\DTO\ExtAddrobjPointDTO;
use App\DTO\ExtAddrobjSynonymDTO;
use App\Entity\ExtAddrobj;
use App\Entity\ExtAddrobjPoint;
use App\Entity\ExtAddrobjSynonym;
use App\Manager\ExtAddrobjManager;
use App\Manager\ExtAddrobjPointManager;
use App\Manager\ExtAddrobjSynonymManager;
use Doctrine\Common\Collections\ArrayCollection;

class ExtAddrobjService
{
    private ExtAddrobjManager $extAddrobjManager;
    private ExtAddrobjPointManager $extAddrobjPointManager;
    private ExtAddrobjSynonymManager $extAddrobjSynonymManager;

    public function __construct(
        ExtAddrobjManager $extAddrobjManager,
        ExtAddrobjPointManager $extAddrobjPointManager,
        ExtAddrobjSynonymManager $extAddrobjSynonymManager
    ) {
        $this->extAddrobjManager = $extAddrobjManager;
        $this->extAddrobjPointManager = $extAddrobjPointManager;
        $this->extAddrobjSynonymManager = $extAddrobjSynonymManager;
    }

    public function getOne(int $objectid): ?ExtAddrobj
    {
        return $this->extAddrobjManager->getOne($objectid);
    }

    public function getPointOne(int $id): ?ExtAddrobjPoint
    {
        return $this->extAddrobjPointManager->getOne($id);
    }

    public function getSynonymOne(int $id): ?ExtAddrobjSynonym
    {
        return $this->extAddrobjSynonymManager->getOne($id);
    }

    /**
     * @return ExtAddrobj[]
     *
     * @psalm-return array<int, ExtAddrobj>
     */
    public function getAll(?int $limit = null, ?int $offset = null): array
    {
        return $this->extAddrobjManager->getAll($limit, $offset);
    }

    /**
     * @return ExtAddrobjPoint[]
     *
     * @psalm-return array<int, ExtAddrobjPoint>
     */
    public function getPointAll(int $objectid): array
    {
        return $this->extAddrobjPointManager->getAll($objectid);
    }

    /**
     * @return ExtAddrobjSynonym[]
     *
     * @psalm-return array<int, ExtAddrobjSynonym>
     */
    public function getSynonymAll(int $objectid): array
    {
        return $this->extAddrobjSynonymManager->getAll($objectid);
    }

    public function add(ExtAddrobjDTO $extAddrobjDto): ?int
    {
        $extAddrobj = $this->extAddrobjManager->add($extAddrobjDto);
        if ($extAddrobj === null) {
            return null;
        }

        foreach ($extAddrobjDto->getSynonymDTOs() as $synonym) {
            $synonym->objectid = $extAddrobj->getObjectid();
            $extAddrobjSynonym = $this->extAddrobjSynonymManager->add($synonym);
            if ($extAddrobjSynonym === null) {
                continue;
            }
            $extAddrobj->addSynonym($extAddrobjSynonym);
        }

        foreach ($extAddrobjDto->getPointDTOs() as $point) {
            $point->objectid = $extAddrobj->getObjectid();
            $extAddrobjPoint = $this->extAddrobjPointManager->add($point);
            if ($extAddrobjPoint === null) {
                continue;
            }
            $extAddrobj->addPoint($extAddrobjPoint);
        }

        return $extAddrobj->getObjectid();
    }

    public function updateById(
        int $objectid,
        ExtAddrobjDTO $extAddrobjDto
    ): bool {

        $extAddrobj = $this->extAddrobjManager->getOne($objectid);
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
        $originalSynonyms = $extAddrobj->getSynonyms();
        $originalPoints = $extAddrobj->getPoints();

        $this->updateSynonymsInExtAddrobj($extAddrobj, $extAddrobjDto);
        $this->updatePointsInExtAddrobj($extAddrobj, $extAddrobjDto);

        $extAddrobj = $this->extAddrobjManager->update(
            $extAddrobj,
            $extAddrobjDto
        );
        if ($extAddrobj === null) {
            return false;
        }

        foreach ($originalSynonyms as $synonym) {
            if ($extAddrobj->getSynonyms()->contains($synonym) === false) {
                $this->extAddrobjSynonymManager->delete($synonym);
            }
        }

        foreach ($originalPoints as $point) {
            if ($extAddrobj->getPoints()->contains($point) === false) {
                $this->extAddrobjPointManager->delete($point);
            }
        }

        return true;
    }

    public function deleteById(int $objectid): bool
    {
        return $this->extAddrobjManager->deleteById($objectid);
    }

    public function addPoint(ExtAddrobjPointDTO $extAddrobjPointDTO): ?int
    {
        $extAddrobjPoint = $this->extAddrobjPointManager->add($extAddrobjPointDTO);
        if ($extAddrobjPoint === null) {
            return null;
        }

        return $extAddrobjPoint->getId();
    }

    public function updatePointById(
        int $id,
        ExtAddrobjPointDTO $extAddrobjPointDTO
    ): ?int {

        $extAddrobjPoint = $this->extAddrobjPointManager->updateById(
            $id,
            $extAddrobjPointDTO
        );
        if ($extAddrobjPoint === null) {
            return null;
        }

        return $extAddrobjPoint->getId();
    }

    public function updatePoint(
        ExtAddrobjPoint $extAddrobjPoint,
        ExtAddrobjPointDTO $extAddrobjPointDTO
    ): ?int {

        $extAddrobjPoint = $this->extAddrobjPointManager->update(
            $extAddrobjPoint,
            $extAddrobjPointDTO
        );

        if ($extAddrobjPoint === null) {
            return null;
        }

        return $extAddrobjPoint->getId();
    }

    public function deletePointById(int $id): bool
    {
        return $this->extAddrobjPointManager->deleteById($id);
    }

    public function deletePointByObjectId(int $objectid): bool
    {
        return $this->extAddrobjPointManager->deleteByObjectId($objectid);
    }

    public function addSynonym(ExtAddrobjSynonymDTO $extAddrobjSynonymDTO): ?int
    {
        $extAddrobjSynonym = $this->extAddrobjSynonymManager->add($extAddrobjSynonymDTO);
        if ($extAddrobjSynonym === null) {
            return null;
        }

        return $extAddrobjSynonym->getId();
    }

    public function updateSynonymById(
        int $id,
        ExtAddrobjSynonymDTO $extAddrobjSynonymDTO
    ): ?int {

        $extAddrobjSynonym = $this->extAddrobjSynonymManager->updateById(
            $id,
            $extAddrobjSynonymDTO
        );
        if ($extAddrobjSynonym === null) {
            return null;
        }

        return $extAddrobjSynonym->getId();
    }

    public function updateSynonym(
        ExtAddrobjSynonym $extAddrobjSynonym,
        ExtAddrobjSynonymDTO $extAddrobjSynonymDTO
    ): ?int {

        $extAddrobjSynonym = $this->extAddrobjSynonymManager->update(
            $extAddrobjSynonym,
            $extAddrobjSynonymDTO
        );
        if ($extAddrobjSynonym === null) {
            return null;
        }

        return $extAddrobjSynonym->getId();
    }

    public function deleteSynonymById(int $id): bool
    {
        return $this->extAddrobjSynonymManager->deleteById($id);
    }

    public function deleteSynonymByObjectId(int $objectid): bool
    {
        return $this->extAddrobjSynonymManager->deleteByObjectId($objectid);
    }

    private function updateSynonymsInExtAddrobj(
        ExtAddrobj $extAddrobj,
        ExtAddrobjDTO $extAddrobjDto
    ): void {
        $extAddrobj->setSynonyms(new ArrayCollection());
        foreach ($extAddrobjDto->getSynonymDTOs() as $synonym) {
            $synonym->objectid = $extAddrobj->getObjectid();
            if ($synonym->id !== null) {
                $extAddrobjSynonym = $this->extAddrobjSynonymManager->updateById($synonym->id, $synonym);
            } else {
                $extAddrobjSynonym = $this->extAddrobjSynonymManager->add($synonym);
            }
            if ($extAddrobjSynonym !== null) {
                $extAddrobj->addSynonym($extAddrobjSynonym);
            }
        }
    }

    private function updatePointsInExtAddrobj(
        ExtAddrobj $extAddrobj,
        ExtAddrobjDTO $extAddrobjDto
    ): void {
        $extAddrobj->setPoints(new ArrayCollection());
        foreach ($extAddrobjDto->getPointDTOs() as $point) {
            $point->objectid = $extAddrobj->getObjectid();
            if ($point->id !== null) {
                $extAddrobjPoint = $this->extAddrobjPointManager->updateById($point->id, $point);
            } else {
                $extAddrobjPoint = $this->extAddrobjPointManager->add($point);
            }
            if ($extAddrobjPoint !== null) {
                $extAddrobj->addPoint($extAddrobjPoint);
            }
        }
    }
}
