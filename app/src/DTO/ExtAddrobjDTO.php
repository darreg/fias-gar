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
    private int $objectid;

    /**
     * @Assert\Length(max = 36)
     */
    private ?string $objectguid;

    /**
     * @Assert\PositiveOrZero
     */
    private ?int $precision;

    /**
     * @Assert\Positive
     */
    private ?float $latitude;

    /**
     * @Assert\Positive
     */
    private ?float $longitude;

    /**
     * @Assert\Positive
     */
    private ?int $zoom;

    /**
     * @Assert\Length(max = 255)
     */
    private ?string $alias;

    /**
     * @Assert\Length(max = 255)
     */
    private ?string $anglicism;

    /**
     * @Assert\Length(max = 255)
     */
    private ?string $nominative;

    /**
     * @Assert\Length(max = 255)
     */
    private ?string $genitive;

    /**
     * @Assert\Length(max = 255)
     */
    private ?string $dative;

    /**
     * @Assert\Length(max = 255)
     */
    private ?string $accusative;

    /**
     * @Assert\Length(max = 255)
     */
    private ?string $ablative;

    /**
     * @Assert\Length(max = 255)
     */
    private ?string $prepositive;

    /**
     * @Assert\Length(max = 255)
     */
    private ?string $locative;

    public function __construct(
        int $objectid,
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
        ?string $locative = null
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
    }

    public function getObjectid(): int
    {
        return $this->objectid;
    }

    public function getObjectguid(): ?string
    {
        return $this->objectguid;
    }

    public function getPrecision(): ?int
    {
        return $this->precision;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function getZoom(): ?int
    {
        return $this->zoom;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function getAnglicism(): ?string
    {
        return $this->anglicism;
    }

    public function getNominative(): ?string
    {
        return $this->nominative;
    }

    public function getGenitive(): ?string
    {
        return $this->genitive;
    }

    public function getDative(): ?string
    {
        return $this->dative;
    }

    public function getAccusative(): ?string
    {
        return $this->accusative;
    }

    public function getAblative(): ?string
    {
        return $this->ablative;
    }

    public function getPrepositive(): ?string
    {
        return $this->prepositive;
    }

    public function getLocative(): ?string
    {
        return $this->locative;
    }
}
