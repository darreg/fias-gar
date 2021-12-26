<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use Symfony\Component\Uid\Uuid;
use InvalidArgumentException;
use Stringable;

class UuidValueObject implements Stringable
{
    protected string $value;

    public function __construct(string $value)
    {
        if (!Uuid::isValid($value)) {
            throw new InvalidArgumentException(
                sprintf('<%s> does not allow the value <%s>.', static::class, $value)
            );
        }

        $this->value = $value;
    }

    public static function next(): static
    {
        return new static((string)Uuid::v4());
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isEqual(UuidValueObject $other): bool
    {
        return $this->getValue() === $other->getValue();
    }

    public function __toString(): string
    {
        return $this->getValue();
    }
}
