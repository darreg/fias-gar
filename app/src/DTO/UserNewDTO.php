<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UserNewDTO implements ConstructFromArrayInterface
{
    use ConstructableFromArrayTrait;

    /**
     * @Assert\Length(max = 180)
     * @Assert\NotBlank
     */
    public ?string $email;

    /**
     * @Assert\Length(max = 255)
     * @Assert\NotBlank
     */
    public ?string $name;

    /**
     * @var array<int, string>|null
     */
    public ?array $roles;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min = 6, max = 32)
     * @Assert\Expression(
     *     "value == this.confirmPassword",
     *     message="Пароль и подтверждение пароля должны совпадать"
     * )
     */
    public ?string $password;

    /**
     * @Assert\NotBlank
     * @Assert\Expression(
     *     "value == this.password",
     *     message="Пароль и подтверждение пароля должны совпадать"
     * )
     */
    public ?string $confirmPassword;

    public ?bool $status;

    /**
     * @param array<int, string>|null $roles
     */
    public function __construct(
        ?string $email = null,
        ?string $name = null,
        ?array $roles = null,
        ?string $password = null,
        ?string $confirmPassword = null,
        ?bool $status = null
    ) {
        $this->email = $email;
        $this->name = $name;
        $this->roles = $roles;
        $this->password = $password;
        $this->confirmPassword = $confirmPassword;
        $this->status = $status;
    }
}
