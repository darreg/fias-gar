<?php

namespace App\Service;

use App\Entity\ExtAddrobj;
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

    /**
     * @return ExtAddrobj[]
     *
     * @psalm-return array<int, ExtAddrobj>
     */
    public function getAll(?int $limit = null, ?int $offset = null): array
    {
        return $this->extAddrobjManager->getAll($limit, $offset);
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

    public function addPoint(
        int $objectid,
        float $latitude,
        float $longitude
    ): bool {
        return $this->extAddrobjPointManager->add(
            $objectid,
            $latitude,
            $longitude
        );
    }

    public function addSynonym(
        int $objectid,
        string $name
    ): bool {

        $this->extAddrobjSynonymManager->add(
            $objectid,
            $name
        );

        return true;
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

    /**
     * @psalm-param array{
     *     objectguid?: string,
     *     precision?: int,
     *     latitude?: float,
     *     longitude?: float,
     *     zoom?: int,
     *     alias?: string,
     *     anglicism?: string,
     *     nominative?: string,
     *     genitive?: string,
     *     dative?: string,
     *     accusative?: string,
     *     ablative?: string,
     *     prepositive?: string,
     *     locative?: string,
     * } $data
     */
    public function updateFieldsById(
        int $objectid,
        array $data
    ): bool {

        $extAddrobj = $this->extAddrobjManager->getOne($objectid);
        if ($extAddrobj === null) {
            return false;
        }

        return $this->updateFields(
            $extAddrobj,
            $data
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

    /**
     * @psalm-param array{
     *     objectguid?: string,
     *     precision?: int,
     *     latitude?: float,
     *     longitude?: float,
     *     zoom?: int,
     *     alias?: string,
     *     anglicism?: string,
     *     nominative?: string,
     *     genitive?: string,
     *     dative?: string,
     *     accusative?: string,
     *     ablative?: string,
     *     prepositive?: string,
     *     locative?: string,
     * } $data
     */
    public function updateFields(
        ExtAddrobj $extAddrobj,
        array $data
    ): bool {

        $this->extAddrobjManager->updateFields(
            $extAddrobj,
            $data
        );

        return true;
    }

    public function deleteById(int $objectid): bool
    {
        return $this->extAddrobjManager->deleteById($objectid);
    }
}
