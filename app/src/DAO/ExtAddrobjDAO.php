<?php

namespace App\DAO;

use App\Entity\ExtAddrobj;
use Doctrine\ORM\EntityManagerInterface;

class ExtAddrobjDAO
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(
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
    ): ExtAddrobj {
        $extHouse = (new ExtAddrobj())
            ->setObjectid($objectid)
            ->setObjectguid($objectguid)
            ->setPrecision($precision)
            ->setLatitude($latitude)
            ->setLongitude($longitude)
            ->setZoom($zoom)
            ->setAlias($alias)
            ->setAnglicism($anglicism)
            ->setNominative($nominative)
            ->setGenitive($genitive)
            ->setDative($dative)
            ->setAccusative($accusative)
            ->setAblative($ablative)
            ->setPrepositive($prepositive)
            ->setLocative($locative);

        $this->entityManager->persist($extHouse);
        $this->entityManager->flush();

        return $extHouse;
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
    ): ExtAddrobj {

        $extAddrobj
            ->setObjectguid($objectguid)
            ->setPrecision($precision)
            ->setLatitude($latitude)
            ->setLongitude($longitude)
            ->setZoom($zoom)
            ->setAlias($alias)
            ->setAnglicism($anglicism)
            ->setNominative($nominative)
            ->setGenitive($genitive)
            ->setDative($dative)
            ->setAccusative($accusative)
            ->setAblative($ablative)
            ->setPrepositive($prepositive)
            ->setLocative($locative);

        $this->entityManager->flush();

        return $extAddrobj;
    }

    public function delete(ExtAddrobj $extAddrobj): void
    {
        $this->entityManager->remove($extAddrobj);
        $this->entityManager->flush();
    }
}
