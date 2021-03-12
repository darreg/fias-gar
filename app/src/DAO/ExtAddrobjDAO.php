<?php

namespace App\DAO;

use App\Entity\ExtAddrobj;
use Doctrine\ORM\EntityManagerInterface;

use function array_key_exists;

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
        $extAddrobj = (new ExtAddrobj())
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

        $this->entityManager->persist($extAddrobj);
        $this->entityManager->flush();

        return $extAddrobj;
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
    ): ExtAddrobj {

        if (array_key_exists('objectguid', $data)) {
            $extAddrobj->setObjectguid($data['objectguid']);
        }

        if (array_key_exists('precision', $data)) {
            $extAddrobj->setPrecision($data['precision']);
        }

        if (array_key_exists('latitude', $data)) {
            $extAddrobj->setLatitude($data['latitude']);
        }

        if (array_key_exists('longitude', $data)) {
            $extAddrobj->setLongitude($data['longitude']);
        }

        if (array_key_exists('zoom', $data)) {
            $extAddrobj->setZoom($data['zoom']);
        }

        if (array_key_exists('alias', $data)) {
            $extAddrobj->setAlias($data['alias']);
        }

        if (array_key_exists('anglicism', $data)) {
            $extAddrobj->setAnglicism($data['anglicism']);
        }

        if (array_key_exists('nominative', $data)) {
            $extAddrobj->setNominative($data['nominative']);
        }

        if (array_key_exists('genitive', $data)) {
            $extAddrobj->setGenitive($data['genitive']);
        }

        if (array_key_exists('dative', $data)) {
            $extAddrobj->setDative($data['dative']);
        }

        if (array_key_exists('accusative', $data)) {
            $extAddrobj->setAccusative($data['accusative']);
        }

        if (array_key_exists('ablative', $data)) {
            $extAddrobj->setAblative($data['ablative']);
        }

        if (array_key_exists('prepositive', $data)) {
            $extAddrobj->setPrepositive($data['prepositive']);
        }

        if (array_key_exists('locative', $data)) {
            $extAddrobj->setLocative($data['locative']);
        }

        $this->entityManager->flush();

        return $extAddrobj;
    }

    public function delete(ExtAddrobj $extAddrobj): void
    {
        $this->entityManager->remove($extAddrobj);
        $this->entityManager->flush();
    }
}
