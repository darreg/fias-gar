<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use InvalidArgumentException;
use Stringable;

class LatLonValueObject implements Stringable
{
    public const DECIMALS = 6;

    private const LATITUDE_MAX = 90;
    private const LATITUDE_MIN = -90;
    private const LONGITUDE_MAX = 180;
    private const LONGITUDE_MIN = -180;

    private float $latitude;

    private float $longitude;

    public function __construct(float $latitude, float $longitude)
    {
        if (!self::isValid($latitude, $longitude)) {
            throw new InvalidArgumentException(
                sprintf('Incorrect coordinate values (%.6f, %.6f)', $latitude, $longitude)
            );
        }
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function __toString(): string
    {
        return $this->latitude . ',' . $this->longitude;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function isEqual(self $latLon): bool
    {
        return
            self::isFloatEqual($this->latitude, $latLon->getLatitude()) &&
            self::isFloatEqual($this->longitude, $latLon->getLongitude());
    }

    public static function fromString(string $latLon): static
    {
        if (substr_count($latLon, ',') !== 1) {
            throw new InvalidArgumentException(
                sprintf('Expected a string to contain a single comma (%s)', $latLon)
            );
        }
        [$latitude, $longitude] = explode(',', $latLon);
        return new self((float)$latitude, (float)$longitude);
    }

    /**
     * @param array<int, float> $latLon
     */
    public static function fromArray(array $latLon): static
    {
        if (\count($latLon) !== 2) {
            throw new InvalidArgumentException(
                sprintf('Expected an array to contain 2 elements. Got: %d.', \count($latLon))
            );
        }
        return new static((float)$latLon[0], (float)$latLon[1]);
    }

    public static function isValid(float $latitude, float $longitude): bool
    {
        return
            $latitude >= self::LATITUDE_MIN && $latitude <= self::LATITUDE_MAX &&
            $longitude >= self::LONGITUDE_MIN && $longitude <= self::LONGITUDE_MAX;
    }

    public static function fixLongitude(float $longitude): float
    {
        while ($longitude < self::LONGITUDE_MIN) {
            $longitude += 360;
        }
        while ($longitude > self::LONGITUDE_MAX) {
            $longitude -= 360;
        }
        return $longitude;
    }

    public static function fixLatitude(float $latitude): float
    {
        while ($latitude < self::LATITUDE_MIN) {
            $latitude += 180;
        }
        while ($latitude > self::LATITUDE_MAX) {
            $latitude -= 180;
        }
        return $latitude;
    }

    private static function isFloatEqual($one, $two): bool
    {
        return bccomp(
            number_format($one, self::DECIMALS),
            number_format($two, self::DECIMALS),
            self::DECIMALS
        ) === 0;
    }
}
