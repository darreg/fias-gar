<?php

declare(strict_types=1);

namespace App\Auth\Test\Domain\User\Entity\Token;

use App\Auth\Domain\User\Entity\Token;
use App\Shared\Domain\Internal\Uuid;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Token::isExpiredTo
 *
 * @internal
 */
final class ExpiresTest extends TestCase
{
    public function testExpires(): void
    {
        $token = new Token(
            Uuid::generate(),
            $expires = new DateTimeImmutable()
        );

        self::assertFalse($token->isExpiredTo($expires->modify('-1 secs')));
        self::assertTrue($token->isExpiredTo($expires));
        self::assertTrue($token->isExpiredTo($expires->modify('+1 secs')));
    }
}
