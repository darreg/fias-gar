<?php

namespace App\Service;

use App\DAO\ExtAddrobjPointDAO;
use App\DTO\ExtAddrobjPointDTO;
use App\DTO\ExtAddrobjSynonymDTO;
use App\Entity\ExtAddrobj;
use App\Entity\ExtAddrobjPoint;
use App\Entity\ExtAddrobjSynonym;
use App\Manager\ExtAddrobjManager;
use App\Manager\ExtAddrobjPointManager;
use App\Manager\ExtAddrobjSynonymManager;

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

    public function add(
        int $objectid,
        ?string $objectguid = null,
        ?int $precision = null,
        ?float $latitude = null,
        ?float $longitude = null,
        ?int $zoom = null,
        ?string $alias = null,
        ?string $anglicism = null,
        ?string $nominative = null,
        ?string $genitive = null,
        ?string $dative = null,
        ?string $accusative = null,
        ?string $ablative = null,
        ?string $prepositive = null,
        ?string $locative = null
    ): bool {

        return $this->extAddrobjManager->add(
            $objectid,
            $objectguid,
            $precision,
            $latitude,
            $longitude,
            $zoom,
            $alias,
            $anglicism,
            $nominative,
            $genitive,
            $dative,
            $accusative,
            $ablative,
            $prepositive,
            $locative
        );
    }

    public function updateById(
        int $objectid,
        ?string $objectguid = null,
        ?int $precision = null,
        ?float $latitude = null,
        ?float $longitude = null,
        ?int $zoom = null,
        ?string $alias = null,
        ?string $anglicism = null,
        ?string $nominative = null,
        ?string $genitive = null,
        ?string $dative = null,
        ?string $accusative = null,
        ?string $ablative = null,
        ?string $prepositive = null,
        ?string $locative = null
    ): bool {

        return $this->extAddrobjManager->updateById(
            $objectid,
            $objectguid,
            $precision,
            $latitude,
            $longitude,
            $zoom,
            $alias,
            $anglicism,
            $nominative,
            $genitive,
            $dative,
            $accusative,
            $ablative,
            $prepositive,
            $locative
        );
    }

    public function update(
        ExtAddrobj $extAddrobj,
        ?string $objectguid = null,
        ?int $precision = null,
        ?float $latitude = null,
        ?float $longitude = null,
        ?int $zoom = null,
        ?string $alias = null,
        ?string $anglicism = null,
        ?string $nominative = null,
        ?string $genitive = null,
        ?string $dative = null,
        ?string $accusative = null,
        ?string $ablative = null,
        ?string $prepositive = null,
        ?string $locative = null
    ): bool {

        return $this->extAddrobjManager->update(
            $extAddrobj,
            $objectguid,
            $precision,
            $latitude,
            $longitude,
            $zoom,
            $alias,
            $anglicism,
            $nominative,
            $genitive,
            $dative,
            $accusative,
            $ablative,
            $prepositive,
            $locative
        );
    }

    public function deleteById(int $objectid): bool
    {
        return $this->extAddrobjManager->deleteById($objectid);
    }

    public function addPoint(ExtAddrobjPointDTO $extAddrobjPointDTO): bool
    {
        return $this->extAddrobjPointManager->add($extAddrobjPointDTO);
    }

    public function updatePointById(
        int $id,
        ExtAddrobjPointDTO $extAddrobjPointDTO
    ): bool {

        return $this->extAddrobjPointManager->updateById(
            $id,
            $extAddrobjPointDTO
        );
    }

    public function updatePoint(
        ExtAddrobjPoint $extAddrobjPoint,
        ExtAddrobjPointDTO $extAddrobjPointDTO
    ): bool {

        return $this->extAddrobjPointManager->update(
            $extAddrobjPoint,
            $extAddrobjPointDTO
        );
    }

    public function deletePointById(int $id): bool
    {
        return $this->extAddrobjPointManager->deleteById($id);
    }

    public function deletePointByObjectId(int $objectid): bool
    {
        return $this->extAddrobjPointManager->deleteByObjectId($objectid);
    }

    public function addSynonym(ExtAddrobjSynonymDTO $extAddrobjSynonymDTO): bool
    {
        return $this->extAddrobjSynonymManager->add($extAddrobjSynonymDTO);
    }

    public function updateSynonymById(
        int $id,
        ExtAddrobjSynonymDTO $extAddrobjSynonymDTO
    ): bool {

        return $this->extAddrobjSynonymManager->updateById(
            $id,
            $extAddrobjSynonymDTO
        );
    }

    public function updateSynonym(
        ExtAddrobjSynonym $extAddrobjSynonym,
        ExtAddrobjSynonymDTO $extAddrobjSynonymDTO
    ): bool {

        return $this->extAddrobjSynonymManager->update(
            $extAddrobjSynonym,
            $extAddrobjSynonymDTO
        );
    }

    public function deleteSynonymById(int $id): bool
    {
        return $this->extAddrobjSynonymManager->deleteById($id);
    }

    public function deleteSynonymByObjectId(int $objectid): bool
    {
        return $this->extAddrobjSynonymManager->deleteByObjectId($objectid);
    }
}
