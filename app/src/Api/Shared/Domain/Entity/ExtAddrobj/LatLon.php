<?php

declare(strict_types=1);

namespace App\Api\Shared\Domain\Entity\ExtAddrobj;

use App\Shared\Domain\ValueObject\LatLonValueObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class LatLon extends LatLonValueObject
{
    /**
     * @ORM\Column(type="decimal", precision=14, scale=11, options={"comment"="Координаты: широта"})
     */
    private float $latitude;

    /**
     * @ORM\Column(type="decimal", precision=14, scale=11, options={"comment"="Координаты: долгота"})
     */
    private float $longitude;

    /**
     * @ORM\Column(type="smallint", nullable=true, options={"comment"="Код точности координат"})
     */
    private ?int $precision;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private ?int $zoom;
}