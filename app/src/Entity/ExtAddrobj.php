<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ExtAddrobj
 *
 * @ORM\Table(
 *     name="ext_addrobj",
 *     indexes={
 *         @ORM\Index(name="ext_addrobj__objectid__ind", columns={"objectid"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ExtAddrobjRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ExtAddrobj
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
     * @ORM\Column(type="string", length=255, nullable=true, options={"comment"="Транслит для URL"})
     */
    private ?string $alias;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"comment"="Англицизм"})
     */
    private ?string $anglicism;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"comment"="Именительный падеж"})
     */
    private ?string $nominative;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"comment"="Родительный падеж"})
     */
    private ?string $genitive;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"comment"="Дательный падеж"})
     */
    private ?string $dative;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"comment"="Винительный падеж"})
     */
    private ?string $accusative;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"comment"="Творительный падеж"})
     */
    private ?string $ablative;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"comment"="Предложный падеж"})
     */
    private ?string $prepositive;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"comment"="Локатив"})
     */
    private ?string $locative;

    /**
     * @var null|Collection|ExtAddrobjSynonym[]
     *
     * @ORM\OneToMany(targetEntity=ExtAddrobjPoint::class, mappedBy="extAddrobj", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="objectid", referencedColumnName="objectid", nullable=true)
     */
    private $polygon;

    /**
     * @var null|Collection|ExtAddrobjSynonym[]
     *
     * @ORM\OneToMany(targetEntity=ExtAddrobjSynonym::class, mappedBy="extAddrobj", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="objectid", referencedColumnName="objectid", nullable=true)
     */
    private $synonym;

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
        $this->polygon = new ArrayCollection();
        $this->synonym = new ArrayCollection();
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

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(?string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    public function getAnglicism(): ?string
    {
        return $this->anglicism;
    }

    public function setAnglicism(?string $anglicism): self
    {
        $this->anglicism = $anglicism;

        return $this;
    }

    public function getNominative(): ?string
    {
        return $this->nominative;
    }

    public function setNominative(?string $nominative): self
    {
        $this->nominative = $nominative;

        return $this;
    }

    public function getGenitive(): ?string
    {
        return $this->genitive;
    }

    public function setGenitive(?string $genitive): self
    {
        $this->genitive = $genitive;

        return $this;
    }

    public function getDative(): ?string
    {
        return $this->dative;
    }

    public function setDative(?string $dative): self
    {
        $this->dative = $dative;

        return $this;
    }

    public function getAccusative(): ?string
    {
        return $this->accusative;
    }

    public function setAccusative(?string $accusative): self
    {
        $this->accusative = $accusative;

        return $this;
    }

    public function getAblative(): ?string
    {
        return $this->ablative;
    }

    public function setAblative(?string $ablative): self
    {
        $this->ablative = $ablative;

        return $this;
    }

    public function getPrepositive(): ?string
    {
        return $this->prepositive;
    }

    public function setPrepositive(?string $prepositive): self
    {
        $this->prepositive = $prepositive;

        return $this;
    }

    public function getLocative(): ?string
    {
        return $this->locative;
    }

    public function setLocative(?string $locative): self
    {
        $this->locative = $locative;

        return $this;
    }

    /**
     * @return Collection|ExtAddrobjPoint[]
     */
    public function getPolygon(): Collection
    {
        return $this->polygon;
    }

    /**
     * @param Collection|ExtAddrobjPoint[] $polygon
     *
     * @return $this
     */
    public function setPolygon($polygon): self
    {
        $this->polygon = $polygon;

        return $this;
    }

    public function addPolygon(ExtAddrobjPoint $polygon): self
    {
        if (!$this->polygon->contains($polygon)) {
            $this->polygon[] = $polygon;
            $polygon->setExtAddrobj($this);
        }

        return $this;
    }

    public function removePolygon(ExtAddrobjPoint $polygon): self
    {
        if ($this->polygon->contains($polygon)) {
            $this->polygon->removeElement($polygon);
            // set the owning side to null (unless already changed)
            if ($polygon->getExtAddrobj() === $this) {
                $polygon->setExtAddrobj(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ExtAddrobjSynonym[]
     */
    public function getSynonym(): Collection
    {
        return $this->synonym;
    }

    /**
     * @param Collection|ExtAddrobjSynonym[] $synonyms
     *
     * @return $this
     */
    public function setSynonym($synonyms): self
    {
        $this->synonym = $synonyms;

        return $this;
    }

    public function addSynonym(ExtAddrobjSynonym $synonym): self
    {
        if (!$this->synonym->contains($synonym)) {
            $this->synonym[] = $synonym;
            $synonym->setExtAddrobj($this);
        }

        return $this;
    }

    public function removeSynonym(ExtAddrobjSynonym $synonym): self
    {
        if ($this->synonym->contains($synonym)) {
            $this->synonym->removeElement($synonym);
            // set the owning side to null (unless already changed)
            if ($synonym->getExtAddrobj() === $this) {
                $synonym->setExtAddrobj(null);
            }
        }

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
