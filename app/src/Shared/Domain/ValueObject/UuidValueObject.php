<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use InvalidArgumentException;
use Stringable;
use Symfony\Component\Uid\Uuid;

class UuidValueObject implements Stringable
{
    private string $value;

    public function __construct(string $value)
    {
        if (!Uuid::isValid($value)) {
            throw new InvalidArgumentException(
                sprintf('<%s> does not allow the value <%s>.', self::class, $value)
            );
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }

    public static function next(): static
    {
        return new self((string)Uuid::v4());
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isEqual(self $other): bool
    {
        return $this->getValue() === $other->getValue();
    }
}
