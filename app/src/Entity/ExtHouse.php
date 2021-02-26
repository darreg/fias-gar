<?php

namespace App\Entity;

use DateTime;
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
 * @ORM\Entity(repositoryClass="App\Repository\ExtHouseRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ExtHouse
{
    /**
     * @ORM\Column(type="bigint")
     * @ORM\Id()
     */
    private ?int $objectid;

    /**
     * @ORM\Column(type="string", length=36, nullable=true)
     */
    private ?string $objectguid;

    /**
     * @ORM\Column(type="smallint", nullable=true, options={"comment"="Код точности координат: 0 — точные координаты, 1 — ближайший дом, 2 — улица, 3 — населенный пункт, 4 — город, 5 — координаты не определены"})
     */
    private ?int $precision;

    /**
     * @ORM\Column(type="decimal", precision=14, scale=11, nullable=true, options={"comment"="Координаты: широта"})
     */
    private ?float $latitude;

    /**
     * @ORM\Column(type="decimal", precision=14, scale=11, nullable=true, options={"comment"="Координаты: долгота"})
     */
    private ?float $longitude;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private ?int $zoom;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private DateTime $updatedAt;



    public function __construct()
    {
        $this->updated_at = new \DateTime();
    }

    public function getObjectid(): ?int
    {
        return $this->objectid;
    }

    public function setObjectid(?int $objectid): self
    {
        $this->objectid = $objectid;

        return $this;
    }

    public function getObjectguid(): ?string
    {
        return $this->objectguid;
    }

    public function setObjectguid(?string $objectguid): self
    {
        $this->objectguid = $objectguid;

        return $this;
    }

    public function getPrecision(): ?int
    {
        return $this->precision;
    }

    public function setPrecision(?int $precision): self
    {
        $this->precision = $precision;

        return $this;
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

    public function getZoom(): ?int
    {
        return $this->zoom;
    }

    public function setZoom(?int $zoom): self
    {
        $this->zoom = $zoom;

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
}
