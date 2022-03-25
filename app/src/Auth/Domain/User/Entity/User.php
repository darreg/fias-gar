<?php

declare(strict_types=1);

namespace App\Auth\Domain\User\Entity;

use App\Shared\Infrastructure\Doctrine\FieldTrait\CreatedAtTrait;
use App\Shared\Infrastructure\Doctrine\FieldTrait\UpdatedAtTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * // * @ORM\Entity(repositoryClass="App\Auth\Domain\User\Repository\UserRepositoryInterface")
 * @ORM\Table(name="users", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"email"}),
 * })
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class User
{
    use CreatedAtTrait;
    use UpdatedAtTrait;

    /**
     * @ORM\Column(type="auth_user_id")
     * @ORM\Id
     */
    private Id $id;

    /**
     * @ORM\Column(type="auth_user_email", nullable=true)
     */
    private Email $email;

    /**
     * @psalm-suppress PropertyNotSetInConstructor
     * @ORM\Column(type="string", name="password_hash", nullable=true)
     */
    private ?string $passwordHash;

    /**
     * @ORM\Column(type="auth_user_status", length=16)
     */
    private Status $status;

    /**
     * @ORM\Embedded(class="Name")
     */
    private Name $name;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles;

    private function __construct(Id $id, Name $name, Email $email, Status $status, array $roles)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->status = $status;
        $this->roles = $roles;
    }

    public static function create(
        Id $id,
        Name $name,
        Email $email,
        Status $status,
        array $roles,
        string $passwordHash
    ): self {
        $user = new self($id, $name, $email, $status, $roles);
        $user->passwordHash = $passwordHash;
        return $user;
    }

    public function isWait(): bool
    {
        return $this->status->isWait();
    }

    public function isActive(): bool
    {
        return $this->status->isActive();
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getPasswordHash(): ?string
    {
        return $this->passwordHash;
    }

    public function getName(): Name
    {
        return $this->name;
    }
}
