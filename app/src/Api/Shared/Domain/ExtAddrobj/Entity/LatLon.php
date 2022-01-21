<?php

declare(strict_types=1);

namespace App\Api\Shared\Domain\ExtAddrobj\Entity;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * @ORM\Embeddable
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class LatLon
{
    /**
     * @psalm-suppress PropertyNotSetInConstructor
     * @ORM\Column(type="decimal", precision=14, scale=11, nullable=true, options={"comment"="Координаты: широта"})
     */
    private ?float $latitude;

    /**
     * @psalm-suppress PropertyNotSetInConstructor
     * @ORM\Column(type="decimal", precision=14, scale=11, nullable=true, options={"comment"="Координаты: долгота"})
     */
    private ?float $longitude;

    /**
     * @ORM\Column(type="smallint", nullable=true, options={"comment"="Код точности координат"})
     */
    private ?int $precision;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private ?int $zoom;

    public function __construct(?float $latitude = null, ?float $longitude = null, ?int $precision = null, ?int $zoom = null)
    {
        Assert::nullOrRange($precision, 0, 5);
        Assert::nullOrRange($zoom, 1, 23);

        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->precision = $precision;
        $this->zoom = $zoom;
    }
}
