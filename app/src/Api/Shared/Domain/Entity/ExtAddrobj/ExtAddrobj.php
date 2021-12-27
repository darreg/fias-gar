<?php

declare(strict_types=1);

namespace App\Api\Shared\Domain\Entity\ExtAddrobj;

use App\Api\Shared\Domain\Entity\ExtAddrobj\Point\Point;
use App\Api\Shared\Domain\Entity\ExtAddrobj\Point\Id as PointId;
use App\Api\Shared\Domain\Entity\ExtAddrobj\Point\LatLon as PointLatLon;
use App\Api\Shared\Domain\Entity\ExtAddrobj\Synonym\Synonym;
use App\Api\Shared\Domain\Entity\ExtAddrobj\Synonym\Id as SynonymId;
use App\Shared\Infrastructure\Doctrine\CreatedAtTrait;
use App\Shared\Infrastructure\Doctrine\UpdatedAtTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use DomainException;


/**
 * @ORM\Table(
 *     name="ext_addrobj",
 *     indexes={
 *         @ORM\Index(name="ext_addrobj__objectid__ind", columns={"objectid"})
 *     }
 * )
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class ExtAddrobj
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
     * @var ArrayCollection|Point[]
     *
     * @ORM\OneToMany(targetEntity=Point::class, mappedBy="extAddrobj", orphanRemoval=true, cascade={"all"})
     * @ORM\JoinColumn(name="objectid", referencedColumnName="objectid", nullable=true)
     */
    private $points;

    /**
     * @var ArrayCollection|Synonym[]
     *
     * @ORM\OneToMany(targetEntity=Synonym::class, mappedBy="extAddrobj", orphanRemoval=true, cascade={"all"})
     * @ORM\JoinColumn(name="objectid", referencedColumnName="objectid", nullable=true)
     */
    private $synonyms;

    public function __construct(
        int $objectid,
        string $objectguid,
        LatLon $latLon,
        ?string $alias,
        ?string $anglicism,
        ?string $nominative,
        ?string $genitive,
        ?string $dative,
        ?string $accusative,
        ?string $ablative,
        ?string $prepositive,
        ?string $locative
    ) {
        $this->objectid = $objectid;
        $this->objectguid = $objectguid;
        $this->latLon = $latLon;
        $this->alias = $alias;
        $this->anglicism = $anglicism;
        $this->nominative = $nominative;
        $this->genitive = $genitive;
        $this->dative = $dative;
        $this->accusative = $accusative;
        $this->ablative = $ablative;
        $this->prepositive = $prepositive;
        $this->locative = $locative;
        $this->points = new ArrayCollection();
        $this->synonyms = new ArrayCollection();
    }

    /**
     * @throws DomainException
     */
    public function addPoint(PointId $id, float $latitude, float $longitude): void
    {
        $latLon = PointLatLon::fromArray([$latitude, $longitude]);

        foreach ($this->points as $point) {
            if ($point->getLatLon()->isEqual($latLon)) {
                throw new DomainException('Point already exists.');
            }
        }
        $this->points->add(new Point($this, $id, $latLon));
    }

    /**
     * @throws DomainException
     */
    public function editPoint(PointId $id, float $latitude, float $longitude): void
    {
        $latLon = PointLatLon::fromArray([$latitude, $longitude]);

        foreach ($this->points as $current) {
            if ($current->getId()->isEqual($id)) {
                $current->setLatLon($latLon);
                return;
            }
        }
        throw new DomainException('Point is not found.');
    }

    public function removePoint(PointId $id): void
    {
        foreach ($this->points as $point) {
            if ($point->getId()->isEqual($id)) {
                $this->points->removeElement($point);
                return;
            }
        }
        throw new \DomainException('Point is not found.');
    }

}