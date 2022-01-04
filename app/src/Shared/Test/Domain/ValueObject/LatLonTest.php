<?php

namespace App\Shared\Test\Domain\ValueObject;

use App\Shared\Domain\ValueObject\LatLonValueObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class LatLonTest extends TestCase
{
    /**
     * @dataProvider validationDataProvider
     */
    public function testValidation(float $latitude, float $longitude, bool $expected): void
    {
        $result = LatLonValueObject::isValid($latitude, $longitude);
        self::assertEquals($expected, $result, "{$latitude}, {$longitude}");
    }

    /**
     * @return list<array{float, float, bool}>
     */
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
