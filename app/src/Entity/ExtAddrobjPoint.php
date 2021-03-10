<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExtAddrobjPoint
 *
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
 * @ORM\Entity(repositoryClass="App\Repository\ExtAddrobjPointRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @psalm-suppress MissingConstructor
 */
class ExtAddrobjPoint
{
    use CreatedAtTrait;
    use UpdatedAtTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="decimal", precision=14, scale=11, options={"comment"="Координаты: широта"})
     */
    private float $latitude;

    /**
     * @ORM\Column(type="decimal", precision=14, scale=11, options={"comment"="Координаты: долгота"})
     */
    private float $longitude;

    /**
     * @ORM\ManyToOne(targetEntity=ExtAddrobj::class, inversedBy="polygon")
     * @ORM\JoinColumn(name="objectid", referencedColumnName="objectid")
     */
    private ExtAddrobj $extAddrobj;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getExtAddrobj(): ExtAddrobj
    {
        return $this->extAddrobj;
    }

    public function setExtAddrobj(ExtAddrobj $extAddrobj): self
    {
        $this->extAddrobj = $extAddrobj;

        return $this;
    }

    public function __toString(): string
    {
        return $this->latitude . ',' . $this->longitude;
    }

    public function toArray(): array
    {
        return [
            'objectid' => $this->getExtAddrobj()->getObjectid(),
            'latitude' => $this->getLatitude(),
            'longitude' => $this->getLongitude(),
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
