<?php

declare(strict_types=1);

namespace App\Api\Shared\Domain\ExtAddrobj\ReadModel;

/** @psalm-suppress MissingConstructor */
final class ExtAddrobjRow
{
    public int $objectid;
    public string $objectguid;
    public float $latitude;
    public float $longitude;
    public ?int $precision;
    public ?int $zoom;
    public ?string $alias;
    public ?string $anglicism;
    public ?string $nominative;
    public ?string $genitive;
    public ?string $dative;
    public ?string $accusative;
    public ?string $ablative;
    public ?string $prepositive;
    public ?string $locative;
}
