<?php

namespace App\BackOffice\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ExtHouseDTO implements ConstructFromArrayInterface
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


    public function __construct(
        ?int $objectid = null,
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
}
