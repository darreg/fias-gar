<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ExtAddrobjSynonymDTO implements ConstructFromArrayInterface
{
    use ConstructableFromArrayTrait;

    /**
     * @Assert\NotBlank
     * @Assert\Positive
     */
    private int $objectid;

    /**
     * @Assert\NotBlank
     * @Assert\Length(max = 255)
     */
    private string $name;

    public function __construct(
        int $objectid,
        string $name
    ) {
        $this->objectid = $objectid;
        $this->name = $name;
    }

    public function getObjectid(): int
    {
        return $this->objectid;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
