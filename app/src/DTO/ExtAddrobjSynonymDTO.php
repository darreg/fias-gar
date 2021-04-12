<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ExtAddrobjSynonymDTO implements ConstructFromArrayInterface
{
    use ConstructableFromArrayTrait;

    /**
     * @Assert\Positive
     */
    public ?int $objectid;

    /**
     * @Assert\Length(max = 255)
     */
    public ?string $name;

    public function __construct(
        ?int $objectid = null,
        ?string $name = null
    ) {
        $this->objectid = $objectid;
        $this->name = $name;
    }
}
