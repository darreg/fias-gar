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
     * @Assert\Positive
     */
    public ?float $latitude;

    /**
     * @Assert\Positive
     */
    public ?float $longitude;

    public function __construct(
        ?int $id = null,        
        ?int $objectid = null,
        ?float $latitude = null,
        ?float $longitude = null
    ) {
        $this->id = $id;        
        $this->objectid = $objectid;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }
}
