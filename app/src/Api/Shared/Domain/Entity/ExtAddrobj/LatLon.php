<?php

declare(strict_types=1);

namespace App\Api\Shared\Domain\Entity\ExtAddrobj;

use App\Shared\Domain\ValueObject\LatLonValueObject;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * @ORM\Embeddable
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class LatLon extends LatLonValueObject
{
    /**
     * @psalm-suppress PropertyNotSetInConstructor
     * @ORM\Column(type="decimal", precision=14, scale=11, options={"comment"="Координаты: широта"})
     */
    protected float $latitude;

    /**
     * @psalm-suppress PropertyNotSetInConstructor
     * @ORM\Column(type="decimal", precision=14, scale=11, options={"comment"="Координаты: долгота"})
     */
    protected float $longitude;

    /**
     * @ORM\Column(type="smallint", nullable=true, options={"comment"="Код точности координат"})
     */
    protected ?int $precision;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected ?int $zoom;

    public function __construct(float $latitude, float $longitude, ?int $precision = null, ?int $zoom = null)
    {
        Assert::nullOrRange($precision, 0, 5);
        Assert::nullOrRange($zoom, 1, 23);

        $this->precision = $precision;
        $this->zoom = $zoom;

        parent::__construct($latitude, $longitude);
    }

    public static function fromString(string $latLon): self
    {
        return self::fromArray(parent::fromStringRaw($latLon));
    }

    public static function fromArray(array $latLon): self
    {
        [$latitude, $longitude] = parent::fromArrayRaw($latLon);
        return new self($latitude, $longitude);
    }
}
