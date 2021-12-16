<?php

namespace App\BackOffice\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ExtAddrobjSynonymDTO implements ConstructFromArrayInterface
{
    use ConstructableFromArrayTrait;

    /**
     * @Assert\Positive
     */
    public ?int $id;

    /**
     * @Assert\NotBlank
     * @Assert\Positive
     */
    public ?int $objectid;

    /**
     * @Assert\NotBlank
     * @Assert\Length(max = 255)
     */
    public string $name;

    public function __construct(
        string $name,
        ?int $id = null,
        ?int $objectid = null
    ) {
        $this->id = $id;
        $this->objectid = $objectid;
        $this->name = $name;
    }
}
