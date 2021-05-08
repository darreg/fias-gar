<?php

namespace App\DTO;

use DateTime;
use Symfony\Component\Validator\Constraints as Assert;

class ApiTokenNewDTO implements ConstructFromArrayInterface
{
    use ConstructableFromArrayTrait;

    /**
     * @Assert\NotBlank
     */
    public ?int $user;

    /**
     * @Assert\Length(max = 255)
     */
    public ?string $name;

    /**
     * @Assert\Length(max = 255)
     * @Assert\NotBlank
     */
    public ?string $token;

    /**
     * @Assert\NotBlank
     */
    public ?string $expiresAt;

    public ?bool $status;


    public function __construct(
        ?int $user = null,
        ?string $name = null,
        ?string $token = null,
        ?string $expiresAt = null,
        ?bool $status = null
    ) {
        $this->user = $user;
        $this->name = $name;
        $this->token = $token;
        $this->expiresAt = $expiresAt;
        $this->status = $status;
    }
}
