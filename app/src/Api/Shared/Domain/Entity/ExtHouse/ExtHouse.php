<?php

namespace App\Api\Shared\Domain\Entity\ExtHouse;

use App\Shared\Infrastructure\Doctrine\CreatedAtTrait;
use App\Shared\Infrastructure\Doctrine\UpdatedAtTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * ExtHouse
 *
 * @ORM\Table(
 *     name="ext_house",
 *     indexes={
 *         @ORM\Index(name="ext_house__objectid__ind", columns={"objectid"})
 *     }
 * )
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @psalm-suppress MissingConstructor
 */
class ExtHouse
{
    use CreatedAtTrait;
    use UpdatedAtTrait;

    /**
     * @ORM\Id()
     * @ORM\Column(type="ext_addrobj_id")
     */
    private Id $id;

    /**
     * @ORM\Embedded(class="House")
     */
    private House $house;

    /**
     * @ORM\Embedded(class="LatLon")
     */
    private LatLon $latLon;

    public function __construct(Id $id, House $house, LatLon $latLon)
    {
        $this->id = $id;
        $this->house = $house;
        $this->latLon = $latLon;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getHouse(): House
    {
        return $this->house;
    }

    public function getLatLon(): LatLon
    {
        return $this->latLon;
    }
}
