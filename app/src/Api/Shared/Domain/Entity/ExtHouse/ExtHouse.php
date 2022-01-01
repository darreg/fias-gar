<?php

namespace App\Api\Shared\Domain\Entity\ExtHouse;

use App\Shared\Infrastructure\Doctrine\FieldTrait\CreatedAtTrait;
use App\Shared\Infrastructure\Doctrine\FieldTrait\UpdatedAtTrait;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * ExtHouse.
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
     * @ORM\Column(type="bigint")
     */
    private int $objectid;

    /**
     * @ORM\Column(type="string", length=36, nullable=true)
     */
    private string $objectguid;

    /**
     * @ORM\Embedded(class="LatLon", columnPrefix=false)
     */
    private LatLon $latLon;

    public function __construct(
        int $objectid,
        string $objectguid,
        LatLon $latLon
    ) {
        Assert::notEmpty($objectid);
        Assert::notEmpty($objectguid);

        $this->objectid = $objectid;
        $this->objectguid = $objectguid;
        $this->latLon = $latLon;
    }

    public function getObjectid(): int
    {
        return $this->objectid;
    }

    public function getObjectguid(): string
    {
        return $this->objectguid;
    }

    public function getLatLon(): LatLon
    {
        return $this->latLon;
    }
}
