<?php

declare(strict_types=1);

namespace App\Api\Shared\Domain\Entity\ExtHouse;

use App\Shared\Domain\Exception\EntityNotFoundException;

interface ExtHouseRepositoryInterface
{
    /**
     * @throws EntityNotFoundException
     */
    public function findOrFail(int $objectid): ExtHouse;

    public function add(ExtHouse $extHouse): void;

    public function remove(ExtHouse $extHouse): void;
}
