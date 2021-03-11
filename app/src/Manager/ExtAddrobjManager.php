<?php

namespace App\Manager;

use App\DAO\ExtAddrobjDAO;
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

        try {
            $this->extAddrobjDao->create(
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
        } catch (\Exception $e) {
            //TODO log
            return false;
        }

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

        $extAddrobj = $this->extAddrobjRepository->find($objectid);
        if ($extAddrobj === null) {
            return false;
        }

        return $this->update(
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
    public function updateFieldsById(
        int $objectid,
        array $data
    ): bool {

        $extAddrobj = $this->extAddrobjRepository->find($objectid);
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

        try {
            $this->extAddrobjDao->update(
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
        } catch (\Exception $e) {
            //TODO log
            return false;
        }

        return true;
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

        try {
            $this->extAddrobjDao->updateFields(
                $extAddrobj,
                $data
            );
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
