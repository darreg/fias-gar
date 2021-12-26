<?php

declare(strict_types=1);

namespace App\Api\Shared\Domain\Entity\ExtAddrobj;

use App\Api\Shared\Domain\Entity\ExtAddrobj\Point\Point;
use App\Api\Shared\Domain\Entity\ExtAddrobj\Synonym\Synonym;
use App\Shared\Infrastructure\Doctrine\CreatedAtTrait;
use App\Shared\Infrastructure\Doctrine\UpdatedAtTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


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
     * @ORM\Column(type="ext_addrobj_id")
     */
    private Id $id;

    /**
     * @ORM\Embedded(class="Addrobj")
     */
    private Addrobj $addrobj;

    /**
     * @ORM\Embedded(class="LatLon")
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
     * @var ArrayCollection<int, Point>
     *
     * @ORM\OneToMany(targetEntity=Point::class, mappedBy="extAddrobj", orphanRemoval=true, cascade={"all"})
     * @ORM\JoinColumn(name="objectid", referencedColumnName="objectid", nullable=true)
     */
    private ArrayCollection $points;

    /**
     * @var ArrayCollection<int, Synonym>
     *
     * @ORM\OneToMany(targetEntity=Synonym::class, mappedBy="extAddrobj", orphanRemoval=true, cascade={"all"})
     * @ORM\JoinColumn(name="objectid", referencedColumnName="objectid", nullable=true)
     */
    private ArrayCollection $synonyms;

    public function __construct(
        Id $id,
        Addrobj $addrobj,
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
        $this->id = $id;
        $this->addrobj = $addrobj;
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


}