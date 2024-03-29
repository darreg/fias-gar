<?php

declare(strict_types=1);

namespace App\Api\Shared\Domain\ExtAddrobj\Entity\Point;

use App\Shared\Domain\ValueObject\LatLonValueObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 * @psalm-suppress MissingConstructor
 */
final class LatLon extends LatLonValueObject
{
    /**
     * @ORM\Column(type="decimal", precision=14, scale=11, options={"comment"="Координаты: широта"})
     */
    protected float $latitude;

    /**
     * @ORM\Column(type="decimal", precision=14, scale=11, options={"comment"="Координаты: долгота"})
     */
    protected float $longitude;

    public static function fromString(string $latLon): self
    {
        return new self(...parent::fromStringRaw($latLon));
    }

    public static function fromArray(array $latLon): self
    {
        return new self(...parent::fromArrayRaw($latLon));
    }
}
