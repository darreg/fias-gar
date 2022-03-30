<?php

declare(strict_types=1);

namespace App\Auth\Domain\User\Entity;

use Webmozart\Assert\Assert;

final class Status
{
    public const WAIT = 'wait';
    public const ACTIVE = 'active';
    public const BLOCKED = 'blocked';

    private string $name;

    public function __construct(string $name)
    {
        Assert::oneOf($name, [
            self::WAIT,
            self::ACTIVE,
            self::BLOCKED,
        ]);
        $this->name = $name;
    }

    public static function wait(): self
    {
        return new self(self::WAIT);
    }

    public static function active(): self
    {
        return new self(self::ACTIVE);
    }

    public static function blocked(): self
    {
        return new self(self::BLOCKED);
    }

    public function isWait(): bool
    {
        return $this->name === self::WAIT;
    }

    public function isActive(): bool
    {
        return $this->name === self::ACTIVE;
    }

    public function isBlocked(): bool
    {
        return $this->name === self::BLOCKED;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
