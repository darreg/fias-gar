<?php

namespace App\Api\Shared\Domain\Entity\ExtAddrobj\Point;

use App\Api\Shared\Domain\Entity\ExtAddrobj\ExtAddrobj;
use App\Shared\Infrastructure\Doctrine\CreatedAtTrait;
use App\Shared\Infrastructure\Doctrine\UpdatedAtTrait;
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
        $this->id = $id;
        $this->extAddrobj = $extAddrobj;
        $this->latLon = $latLon;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getLatLon(): LatLon
    {
        return $this->latLon;
    }

    public function __toString(): string
    {
        return (string)$this->latLon;
    }
}
