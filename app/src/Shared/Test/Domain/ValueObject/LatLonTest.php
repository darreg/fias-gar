<?php

namespace App\Shared\Test\Domain\ValueObject;

use App\Shared\Domain\ValueObject\LatLonValueObject;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class LatLonTest extends TestCase
{
    public function testCreateFromString(): void
    {
        $result = LatLonValueObject::fromString('11.2222,22.3333');
        self::assertEquals(11.2222, $result->getLatitude());
        self::assertEquals(22.3333, $result->getLongitude());
    }

    public function testCreateFromStringException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        LatLonValueObject::fromString('11.2222');
    }

    public function testCreateFromArray(): void
    {
        $result = LatLonValueObject::fromArray([11.2222, 22.3333]);
        self::assertEquals(11.2222, $result->getLatitude());
        self::assertEquals(22.3333, $result->getLongitude());
    }

    public function testCreateFromArrayException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        LatLonValueObject::fromArray([11.2222]);
    }

    /**
     * @dataProvider validationDataProvider
     * @param mixed $latitude
     * @param mixed $longitude
     * @param mixed $expected
     */
    public function testValidation($latitude, $longitude, $expected): void
    {
        $result = LatLonValueObject::isValid($latitude, $longitude);
        self::assertEquals($expected, $result, "{$latitude}, {$longitude}");
    }

    public function validationDataProvider(): array
    {
        return [
            [0.0, 0.0, true],
            [90.0, 90.0, true],
            [-90.0, 180.0, true],
            [90.0, -180.0, true],
            [-91.0, 180.0, false],
            [90.0, 181.0, false],
            [11.2222, 22.3333, true],
        ];
    }
}
