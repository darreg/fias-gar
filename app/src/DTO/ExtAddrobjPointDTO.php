<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ExtAddrobjPointDTO implements ConstructFromArrayInterface
{
    use ConstructableFromArrayTrait;

    /**
     * @Assert\Positive
     */
    public ?int $id;

    /**
     * @Assert\Positive
     */
    public ?int $objectid;

    /**
     * @Assert\NotBlank
     * @Assert\Positive
     */
    public float $latitude;

    /**
     * @Assert\NotBlank
     * @Assert\Positive
     */
    public float $longitude;

    public function __construct(
        float $latitude,
        float $longitude,
        ?int $id = null,
        ?int $objectid = null
    ) {
        $this->id = $id;
        $this->objectid = $objectid;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }
}
