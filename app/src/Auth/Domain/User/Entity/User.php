<?php

declare(strict_types=1);

namespace App\Auth\Domain\User\Entity;

use App\Auth\Domain\Exception\ChangeEmailException;
use App\Auth\Domain\Exception\ChangePasswordException;
use App\Auth\Domain\Exception\ConfirmationException;
use App\Auth\Domain\Exception\RemoveException;
use App\Auth\Domain\Exception\ResetPasswordException;
use App\Auth\Domain\User\Service\PasswordHasherInterface;
use App\Shared\Infrastructure\Doctrine\FieldTrait\CreatedAtTrait;
use App\Shared\Infrastructure\Doctrine\FieldTrait\UpdatedAtTrait;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use DomainException;

/**
 * @ORM\Table(name="users", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"email"}),
 * })
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
final class User
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
     * @ORM\Column(type="auth_user_main_role", length=16)
     */
    private MainRole $mainRole;
    /**
     * @ORM\Column(type="json")
     */
    private array $roles;
    /**
     * @ORM\Embedded(class="Token")
     */
    private ?Token $joinConfirmToken = null;
    /**
     * @ORM\Embedded(class="Token")
     */
    private ?Token $passwordResetToken = null;
    /**
     * @ORM\Embedded(class="Token")
     */
    private ?Token $emailChangeToken = null;
    /**
     * @ORM\Column(type="auth_user_email", nullable=true)
     */
    private ?Email $newEmail = null;

    private function __construct(Id $id, Name $name, Email $email, Status $status)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->status = $status;
        $this->mainRole = MainRole::user();
        $this->roles = [];
    }

    public static function joinByEmail(
        Id $id,
        Name $name,
        Email $email,
        string $passwordHash,
        Token $token
    ): self {
        $user = new self($id, $name, $email, Status::wait());
        $user->passwordHash = $passwordHash;
        $user->joinConfirmToken = $token;
        return $user;
    }

    /**
     * @throws DomainException
     */
    public function joinConfirm(string $token, DateTimeImmutable $date): void
    {
        if ($this->joinConfirmToken === null) {
            throw new ConfirmationException('Confirmation is not required.');
        }
        $this->joinConfirmToken->validate($token, $date);
        $this->status = Status::active();
        $this->joinConfirmToken = null;
    }

    /**
     * @throws DomainException
     */
    public function resetPasswordRequest(Token $token, DateTimeImmutable $date): void
    {
        if (!$this->isActive()) {
            throw new ResetPasswordException('User is not active.');
        }
        if ($this->passwordResetToken !== null && !$this->passwordResetToken->isExpiredTo($date)) {
            throw new ResetPasswordException('Resetting is already requested.');
        }
        $this->passwordResetToken = $token;
    }

    /**
     * @throws DomainException
     */
    public function resetPasswordConfirm(string $token, DateTimeImmutable $date, string $passwordHash): void
    {
        if ($this->passwordResetToken === null) {
            throw new ResetPasswordException('Resetting is not requested.');
        }
        $this->passwordResetToken->validate($token, $date);
        $this->passwordResetToken = null;
        $this->passwordHash = $passwordHash;
    }

    /**
     * @throws DomainException
     */
    public function changePassword(string $current, string $new, PasswordHasherInterface $passwordHasher): void
    {
        if ($this->passwordHash === null) {
            throw new ChangePasswordException('User does not have an old password.');
        }
        if (!$passwordHasher->isPasswordValid($this->passwordHash, $current)) {
            throw new ChangePasswordException('Incorrect current password.');
        }
        $this->passwordHash = $passwordHasher->hashPassword($new);
    }

    /**
     * @throws DomainException
     */
    public function changeEmailRequest(Token $token, DateTimeImmutable $date, Email $email): void
    {
        if (!$this->isActive()) {
            throw new ChangeEmailException('User is not active.');
        }
        if ($this->email->isEqualTo($email)) {
            throw new ChangeEmailException('Email is already same.');
        }
        if ($this->emailChangeToken !== null && !$this->emailChangeToken->isExpiredTo($date)) {
            throw new ChangeEmailException('Changing is already requested.');
        }
        $this->newEmail = $email;
        $this->emailChangeToken = $token;
    }

    /**
     * @throws DomainException
     */
    public function changeEmailConfirm(string $token, DateTimeImmutable $date): void
    {
        if ($this->newEmail === null || $this->emailChangeToken === null) {
            throw new ChangeEmailException('Changing is not requested.');
        }
        $this->emailChangeToken->validate($token, $date);
        $this->email = $this->newEmail;
        $this->newEmail = null;
        $this->emailChangeToken = null;
    }

    public function remove(): void
    {
        if (!$this->isWait()) {
            throw new RemoveException('Unable to remove active user.');
        }
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

    public function getMainRole(): MainRole
    {
        return $this->mainRole;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = $this->mainRole->getName();
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

    public function getJoinConfirmToken(): ?Token
    {
        return $this->joinConfirmToken;
    }

    public function getPasswordResetToken(): ?Token
    {
        return $this->passwordResetToken;
    }

    public function getEmailChangeToken(): ?Token
    {
        return $this->emailChangeToken;
    }
}
