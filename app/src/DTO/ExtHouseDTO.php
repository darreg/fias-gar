<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ExtHouseDTO implements ConstructFromArrayInterface
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


    public function __construct(
        int $objectid,
        ?string $objectguid = null,
        ?int $precision = null,
        ?float $latitude = null,
        ?float $longitude = null,
        ?int $zoom = null
    ) {
        $this->objectid = $objectid;
        $this->objectguid = $objectguid;
        $this->precision = $precision;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->zoom = $zoom;
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
}
