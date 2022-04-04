<?php

declare(strict_types=1);

namespace App\Auth\Test\Domain\User\Entity;

use App\Auth\Domain\User\Entity\MainRole;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MainRole
 *
 * @internal
 */
final class MainRoleTest extends TestCase
{
    public function testSuccess(): void
    {
        $role = new MainRole($name = MainRole::ADMIN);

        self::assertEquals($name, $role->getName());
    }

    public function testIncorrect(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new MainRole('none');
    }

    public function testEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new MainRole('');
    }

    public function testUserFactory(): void
    {
        $role = MainRole::user();

        self::assertEquals(MainRole::USER, $role->getName());
    }
}
