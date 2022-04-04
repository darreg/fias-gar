<?php

declare(strict_types=1);

namespace App\Auth\Test\Domain\User\Entity\Token;

use App\Auth\Domain\User\Entity\Token;
use App\Shared\Domain\Internal\Uuid;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Token::validate
 *
 * @internal
 */
final class ValidateTest extends TestCase
{
    /**
     * @doesNotPerformAssertions
     */
    public function testSuccess(): void
    {
        $token = new Token(
            $value = Uuid::generate(),
            $expires = new DateTimeImmutable()
        );

        $token->validate($value, $expires->modify('-1 secs'));
    }

    public function testWrong(): void
    {
        $token = new Token(
            Uuid::generate(),
            $expires = new DateTimeImmutable()
        );

        $this->expectExceptionMessage('Token is invalid');
        $token->validate(Uuid::generate(), $expires->modify('-1 secs'));
    }

    public function testExpired(): void
    {
        $token = new Token(
            $value = Uuid::generate(),
            $expires = new DateTimeImmutable()
        );

        $this->expectExceptionMessage('Token is expired');
        $token->validate($value, $expires->modify('+1 secs'));
    }
}
