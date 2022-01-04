<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\Internal\Uuid;
use InvalidArgumentException;
use Stringable;

abstract class UuidValueObject implements Stringable
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

    final public function getValue(): string
    {
        return $this->value;
    }

    final public function isEqual(self $other): bool
    {
        return $this->getValue() === $other->getValue();
    }

    abstract public static function next(): self;

    protected static function randomUuid(): string
    {
        return Uuid::generate();
    }
}
