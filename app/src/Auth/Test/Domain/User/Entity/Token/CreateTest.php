<?php

declare(strict_types=1);

namespace App\Auth\Test\Domain\User\Entity\Token;

use App\Auth\Domain\User\Entity\Token;
use App\Shared\Domain\Internal\Uuid;
use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Token
 *
 * @internal
 */
final class CreateTest extends TestCase
{
    public function testSuccess(): void
    {
        $token = new Token(
            $value = Uuid::generate(),
            $expires = new DateTimeImmutable()
        );

        self::assertEquals($value, $token->getValue());
        self::assertEquals($expires, $token->getExpires());
    }

    public function testCase(): void
    {
        $value = Uuid::generate();

        $token = new Token(mb_strtoupper($value), new DateTimeImmutable());

        self::assertEquals($value, $token->getValue());
    }

    public function testIncorrect(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Token('12345', new DateTimeImmutable());
    }

    public function testEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Token('', new DateTimeImmutable());
    }
}
