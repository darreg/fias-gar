<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ExtAddrobjPointDTO implements ConstructFromArrayInterface
{
    use ConstructableFromArrayTrait;

    /**
     * @Assert\NotBlank
     * @Assert\Positive
     */
    private int $objectid;

    /**
     * @Assert\NotBlank
     * @Assert\Positive
     */
    private float $latitude;

    /**
     * @Assert\NotBlank
     * @Assert\Positive
     */
    private float $longitude;

    public function __construct(
        int $objectid,
        float $latitude,
        float $longitude
    ) {
        $this->objectid = $objectid;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function getObjectid(): int
    {
        return $this->objectid;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }
}
