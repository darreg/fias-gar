<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class AdminDTO implements ConstructFromArrayInterface
{
    use ConstructableFromArrayTrait;

    /**
     * @Assert\NotBlank
     * @Assert\Positive
     */
    public ?int $id;

    /**
     * @Assert\Length(max = 180)
     * @Assert\NotBlank
     */
    public ?string $email;

    /**
     * @Assert\Length(max = 255)
     */
    public ?string $name;

    /**
     * @Assert\NotBlank
     */
    public ?array $roles;

    /**
     * @Assert\Length(max = 255)
     * @Assert\NotBlank
     */
    public ?string $password;

    public ?bool $status;


    public function __construct(
        ?int $id = null,
        ?string $email = null,
        ?string $name = null,
        ?array $roles = null,
        ?string $password = null,
        ?bool $status = null
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
        $this->roles = $roles;
        $this->password = $password;
        $this->status = $status;
    }
}
