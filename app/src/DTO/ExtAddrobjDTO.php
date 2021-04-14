<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ExtAddrobjDTO implements ConstructFromArrayInterface
{
    use ConstructableFromArrayTrait;

    /**
     * @Assert\NotBlank
     * @Assert\Positive
     */
    public ?int $objectid;

    /**
     * @Assert\Length(min = 36, max = 36)
     */
    public ?string $objectguid;

    /**
     * @Assert\PositiveOrZero
     */
    public ?int $precision;

    /**
     * @Assert\Positive
     */
    public ?float $latitude;

    /**
     * @Assert\Positive
     */
    public ?float $longitude;

    /**
     * @Assert\Positive
     */
    public ?int $zoom;

    /**
     * @Assert\Length(max = 255)
     */
    public ?string $alias;

    /**
     * @Assert\Length(max = 255)
     */
    public ?string $anglicism;

    /**
     * @Assert\Length(max = 255)
     */
    public ?string $nominative;

    /**
     * @Assert\Length(max = 255)
     */
    public ?string $genitive;

    /**
     * @Assert\Length(max = 255)
     */
    public ?string $dative;

    /**
     * @Assert\Length(max = 255)
     */
    public ?string $accusative;

    /**
     * @Assert\Length(max = 255)
     */
    public ?string $ablative;

    /**
     * @Assert\Length(max = 255)
     */
    public ?string $prepositive;

    /**
     * @Assert\Length(max = 255)
     */
    public ?string $locative;

    /**
     * @var array<int, array>
     */
    public array $synonyms;

    /**
     * @var array<int, array>
     */
    public array $points;

    /**
     * @param array<int, array> $synonyms
     * @param array<int, array> $points
     */
    public function __construct(
        ?int $objectid = null,
        ?string $objectguid = null,
        ?int $precision = null,
        ?float $latitude = null,
        ?float $longitude = null,
        ?int $zoom = null,
        ?string $alias = null,
        ?string $anglicism = null,
        ?string $nominative = null,
        ?string $genitive = null,
        ?string $dative = null,
        ?string $accusative = null,
        ?string $ablative = null,
        ?string $prepositive = null,
        ?string $locative = null,
        array $synonyms = [],
        array $points = []
    ) {
        $this->objectid = $objectid;
        $this->objectguid = $objectguid;
        $this->precision = $precision;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->zoom = $zoom;
        $this->alias = $alias;
        $this->anglicism = $anglicism;
        $this->nominative = $nominative;
        $this->genitive = $genitive;
        $this->dative = $dative;
        $this->accusative = $accusative;
        $this->ablative = $ablative;
        $this->prepositive = $prepositive;
        $this->locative = $locative;
        $this->synonyms = $synonyms;
        $this->points = $points;
    }

    /**
     * @return array<int, ExtAddrobjSynonymDTO>
     */
    public function getSynonymDTOs(): array
    {
        return array_map(static fn(array $synonym) => ExtAddrobjSynonymDTO::fromArray($synonym), $this->synonyms);
    }

    /**
     * @return array<int, ExtAddrobjPointDTO>
     */
    public function getPointDTOs(): array
    {
        return array_map(static fn(array $point) => ExtAddrobjPointDTO::fromArray($point), $this->points);
    }
}
