<?php

declare(strict_types=1);

namespace App\Api\Shared\Domain\Entity\ExtHouse;

interface ExtHouseRepositoryInterface
{
    public function get(int $objectid): ExtHouse;

    public function add(ExtHouse $extHouse): void;

    public function remove(ExtHouse $extHouse): void;
}
