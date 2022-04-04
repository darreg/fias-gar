<?php

declare(strict_types=1);

namespace App\Auth\Test\Domain\User\Entity;

use App\Auth\Domain\User\Entity\Id;
use App\Shared\Domain\Internal\Uuid;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Id
 *
 * @internal
 */
final class IdTest extends TestCase
{
    public function testSuccess(): void
    {
        $id = new Id($value = Uuid::generate());

        self::assertEquals($value, $id->getValue());
    }

    public function testCase(): void
    {
        $value = Uuid::generate();

        $id = new Id(mb_strtoupper($value));

        self::assertEquals($value, $id->getValue());
    }

    public function testGenerate(): void
    {
        $id = Id::next();

        self::assertNotEmpty($id->getValue());
    }

    public function testIncorrect(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Id('12345');
    }

    public function testEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Id('');
    }
}
