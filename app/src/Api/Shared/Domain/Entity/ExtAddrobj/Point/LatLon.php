<?php

declare(strict_types=1);

namespace App\Api\Shared\Domain\Entity\ExtAddrobj\Point;

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
    protected float $latitude;

    /**
     * @ORM\Column(type="decimal", precision=14, scale=11, options={"comment"="Координаты: долгота"})
     */
    protected float $longitude;
}