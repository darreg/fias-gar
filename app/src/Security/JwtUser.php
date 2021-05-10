<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class JwtUser implements UserInterface
{
    private string $username;

    /**
     * @var array<int, string>
     */
    private array $roles;

    /**
     * @param array{username: string, roles: array<int, string>} $credentials
     */
    public function __construct(array $credentials)
    {
        $this->username = $credentials['username'];
        $roles = $credentials['roles'] ?? [];
        $roles[] = 'ROLE_USER';
        $this->roles = array_values(array_unique($roles));
    }

    /**
     * @see UserInterface
     * @return array<int, string>
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getPassword(): ?string
    {
        return '';
    }

    public function getSalt(): ?string
    {
        return '';
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function eraseCredentials(): void
    {
    }
}
