<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Service;

use App\Auth\Domain\Shared\ReadModel\AuthModel;
use App\Auth\Domain\User\Entity\Status;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthIdentity implements UserInterface, EquatableInterface, PasswordAuthenticatedUserInterface
{
    private string $id;
    private string $email;
    private string $password;
    /**
     * @var string[]
     */
    private array $roles;
    private string $status;

    /**
     * @param string[] $roles
     */
    public function __construct(
        string $id,
        string $email,
        string $password,
        array $roles,
        string $status
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->roles = $roles;
        $this->status = $status;
    }

    public static function fromAuthModel(AuthModel $user): self
    {
        return new self(
            $user->id,
            $user->email,
            $user->password_hash ?: '',
            $user->roles,
            $user->status
        );
    }

    public function isActive(): bool
    {
        return $this->status === Status::ACTIVE;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function isEqualTo(UserInterface $user): bool
    {
        if (!$user instanceof self) {
            return false;
        }

        return
            $this->id === $user->id &&
            $this->email === $user->email &&
            $this->password === $user->password &&
            $this->roles === $user->roles &&
            $this->status === $user->status;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }

    public function getSalt(): ?string
    {
        // We're using bcrypt in security.yaml to encode the password, so
        // the salt value is built-in and you don't have to generate one
        // See https://en.wikipedia.org/wiki/Bcrypt

        return null;
    }
}
