<?php

namespace App\Api\Shared\Domain\ExtAddrobj\Entity\Point;

use App\Api\Shared\Domain\ExtAddrobj\Entity\ExtAddrobj;
use App\Shared\Infrastructure\Doctrine\FieldTrait\CreatedAtTrait;
use App\Shared\Infrastructure\Doctrine\FieldTrait\UpdatedAtTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="ext_addrobj_point",
 *     indexes={
 *         @ORM\Index(name="ext_addrobj_point__id__ind", columns={"id"}),
 *         @ORM\Index(name="ext_addrobj_point__objectid__ind", columns={"objectid"})
 *     },
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(
 *              name="ext_addrobj_point__unique__constraint",
 *              columns={"objectid", "latitude", "longitude"}
 *          )
 *     }
 * )
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Point
{
    use CreatedAtTrait;
    use UpdatedAtTrait;

    /**
     * @ORM\ManyToOne(targetEntity=ExtAddrobj::class, inversedBy="points")
     * @ORM\JoinColumn(name="objectid", referencedColumnName="objectid")
     */
    private ExtAddrobj $extAddrobj;

    /**
     * @ORM\Id()
     * @ORM\Column(type="ext_addrobj_point_id")
     */
    private Id $id;

    /**
     * @ORM\Embedded(class="LatLon", columnPrefix=false)
     */
    private LatLon $latLon;

    public function __construct(ExtAddrobj $extAddrobj, Id $id, LatLon $latLon)
    {
        $this->extAddrobj = $extAddrobj;
        $this->id = $id;
        $this->latLon = $latLon;
    }

    public function __toString(): string
    {
        return (string)$this->latLon;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getLatLon(): LatLon
    {
        return $this->latLon;
    }

    public function setLatLon(LatLon $latLon): void
    {
        $this->latLon = $latLon;
    }
}
