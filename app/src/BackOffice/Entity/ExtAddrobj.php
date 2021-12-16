<?php

namespace App\BackOffice\Entity;

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
 *
 * @psalm-suppress MissingConstructor
 * @psalm-suppress PropertyNotSetInConstructor
 */
class ExtAddrobj
{
    use CreatedAtTrait;
    use UpdatedAtTrait;

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
     * @ORM\Column(type="smallint", nullable=true, options={"comment"="Код точности координат"})
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
     * @var Collection|ExtAddrobjPoint[]
     * @psalm-var Collection<int, ExtAddrobjPoint>
     *
     * @ORM\OneToMany(targetEntity=ExtAddrobjPoint::class, mappedBy="extAddrobj", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="objectid", referencedColumnName="objectid", nullable=true)
     */
    private $points;

    /**
     * @var Collection|ExtAddrobjSynonym[]
     * @psalm-var Collection<int, ExtAddrobjSynonym>
     *
     * @ORM\OneToMany(targetEntity=ExtAddrobjSynonym::class, mappedBy="extAddrobj", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="objectid", referencedColumnName="objectid", nullable=true)
     */
    private $synonyms;


    public function __construct()
    {
        $this->points = new ArrayCollection();
        $this->synonyms = new ArrayCollection();
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
     * @psalm-return Collection<int, ExtAddrobjPoint>
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @param Collection|ExtAddrobjPoint[] $points
     * @psalm-param Collection<int, ExtAddrobjPoint> $points
     *
     * @return $this
     */
    public function setPoints($points): self
    {
        $this->points = $points;

        return $this;
    }

    public function addPoint(ExtAddrobjPoint $point): self
    {
        if (!$this->points->contains($point)) {
            $this->points[] = $point;
            $point->setExtAddrobj($this);
        }

        return $this;
    }

    public function removePoint(ExtAddrobjPoint $point): self
    {
        if ($this->points->contains($point)) {
            $this->points->removeElement($point);
        }

        return $this;
    }

    /**
     * @return Collection|ExtAddrobjSynonym[]
     * @psalm-return Collection<int, ExtAddrobjSynonym>
     */
    public function getSynonyms()
    {
        return $this->synonyms;
    }

    /**
     * @param Collection|ExtAddrobjSynonym[] $synonyms
     * @psalm-param Collection<int, ExtAddrobjSynonym> $synonyms
     *
     * @return $this
     */
    public function setSynonyms($synonyms): self
    {
        $this->synonyms = $synonyms;

        return $this;
    }

    public function addSynonym(ExtAddrobjSynonym $synonym): self
    {
        if (!$this->synonyms->contains($synonym)) {
            $this->synonyms[] = $synonym;
            $synonym->setExtAddrobj($this);
        }

        return $this;
    }

    public function removeSynonym(ExtAddrobjSynonym $synonym): self
    {
        if ($this->synonyms->contains($synonym)) {
            $this->synonyms->removeElement($synonym);
        }

        return $this;
    }
}
