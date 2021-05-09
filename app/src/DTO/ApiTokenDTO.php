<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ApiTokenDTO implements ConstructFromArrayInterface
{
    use ConstructableFromArrayTrait;

    /**
     * @Assert\NotBlank
     * @Assert\Positive
     */
    public int $id;

    /**
     * @Assert\NotBlank
     */
    public int $user;

    /**
     * @Assert\Length(max = 255)
     */
    public string $name;

    /**
     * @Assert\Length(max = 255)
     * @Assert\NotBlank
     */
    public string $token;

    /**
     * @Assert\NotBlank
     */
    public string $expiresAt;

    public bool $status;


    public function __construct(
        int $id,
        int $user,
        string $name,
        string $token,
        string $expiresAt,
        bool $status
    ) {
        $this->id = $id;
        $this->user = $user;
        $this->name = $name;
        $this->token = $token;
        $this->expiresAt = $expiresAt;
        $this->status = $status;
    }
}
