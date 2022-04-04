<?php

declare(strict_types=1);

namespace App\Auth\Domain\User\Entity;

use Webmozart\Assert\Assert;

final class MainRole
{
    public const PREFIX = 'MAIN_';

    public const USER = self::PREFIX . 'ROLE_USER';
    public const ADMIN = self::PREFIX . 'MAIN_ROLE_ADMIN';

    private string $name;

    public function __construct(string $name)
    {
        Assert::oneOf($name, [
            self::USER,
            self::ADMIN,
        ]);

        $this->name = $name;
    }

    public static function user(): self
    {
        return new self(self::USER);
    }

    public static function admin(): self
    {
        return new self(self::ADMIN);
    }

    public function isUser(): bool
    {
        return $this->name === self::USER;
    }

    public function isAdmin(): bool
    {
        return $this->name === self::ADMIN;
    }

    public function isEqualTo(self $role): bool
    {
        return $this->getName() === $role->getName();
    }

    public function getName(): string
    {
        return $this->name;
    }
}
