<?php

declare(strict_types=1);

namespace App\Auth\Domain\User\Entity;

use App\Auth\Domain\Exception\ExpiredTokenException;
use App\Auth\Domain\Exception\InvalidTokenException;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * @ORM\Embeddable
 */
final class Token
{
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private string $value;
    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private DateTimeImmutable $expires;

    public function __construct(string $value, DateTimeImmutable $expires)
    {
        Assert::uuid($value);
        $this->value = mb_strtolower($value);
        $this->expires = $expires;
    }

    public function validate(string $value, DateTimeImmutable $date): void
    {
        if (!$this->isEqualTo($value)) {
            throw new InvalidTokenException('Token is invalid');
        }
        if ($this->isExpiredTo($date)) {
            throw new ExpiredTokenException('Token is expired');
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getExpires(): DateTimeImmutable
    {
        return $this->expires;
    }

    public function isExpiredTo(DateTimeImmutable $date): bool
    {
        return $this->expires <= $date;
    }

    private function isEqualTo(string $value): bool
    {
        return $this->value === $value;
    }
}
