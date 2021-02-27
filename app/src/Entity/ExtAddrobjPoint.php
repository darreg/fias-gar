<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * ExtAddrobjPoint
 *
 * @ORM\Table(
 *     name="ext_addrobj_point",
 *     indexes={
 *         @ORM\Index(name="ext_addrobj_point__id__ind", columns={"id"}),
 *         @ORM\Index(name="ext_addrobj_point__objectid__ind", columns={"objectid"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ExtAddrobjPointRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ExtAddrobjPoint
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="decimal", precision=14, scale=11, nullable=true, options={"comment"="Координаты: широта"})
     */
    private ?float $latitude;

    /**
     * @ORM\Column(type="decimal", precision=14, scale=11, nullable=true, options={"comment"="Координаты: долгота"})
     */
    private ?float $longitude;

    /**
     * @ORM\ManyToOne(targetEntity=ExtAddrobj::class, inversedBy="polygon")
     * @ORM\JoinColumn(name="objectid", referencedColumnName="objectid", nullable=true)
     */
    private ?ExtAddrobj $extAddrobj;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private DateTime $updatedAt;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getExtAddrobj(): ?ExtAddrobj
    {
        return $this->extAddrobj;
    }

    public function setExtAddrobj(?ExtAddrobj $extAddrobj): self
    {
        $this->extAddrobj = $extAddrobj;

        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setCreatedAt(): self
    {
        $this->createdAt = new DateTime();

        return $this;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function setUpdatedAt(): self
    {
        $this->updatedAt = new DateTime();

        return $this;
    }

    public function __toString(): string
    {
        return $this->latitude . ',' . $this->longitude;
    }
}
