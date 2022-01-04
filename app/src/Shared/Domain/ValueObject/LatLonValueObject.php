<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use InvalidArgumentException;
use Stringable;

abstract class LatLonValueObject implements Stringable
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

    abstract public static function fromString(string $latLon): self;

    /**
     * @param array<int, float> $latLon
     */
    abstract public static function fromArray(array $latLon): self;
    

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

    protected static function isFloatEqual(float $one, float $two): bool
    {
        return bccomp(
            self::numberFormat($one),
            self::numberFormat($two),
            self::DECIMALS
        ) === 0;
    }

    /**
     * @return numeric-string
     * @throws InvalidArgumentException
     */
    protected static function numberFormat(float $num): string
    {
        $result = number_format($num, self::DECIMALS);
        if (!is_numeric($result)) {
            throw new InvalidArgumentException(
                sprintf('Invalid float value. Got: %d.', $num)
            );
        }

        return $result;
    }

    /**
     * @return list<float>
     */
    protected static function fromStringRaw(string $latLon): array
    {
        if (substr_count($latLon, ',') !== 1) {
            throw new InvalidArgumentException(
                sprintf('Expected a string to contain a single comma (%s)', $latLon)
            );
        }
        $exploded = explode(',', $latLon);

        return [(float)$exploded[0], (float)$exploded[1]];
    }

    /**
     * @return list<float>
     */
    protected static function fromArrayRaw(array $latLon): array
    {
        if (\count($latLon) !== 2) {
            throw new InvalidArgumentException(
                sprintf('Expected an array to contain 2 elements. Got: %d.', \count($latLon))
            );
        }
        return [(float)$latLon[0], (float)$latLon[1]];
    }    
}
