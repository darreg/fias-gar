<?php

declare(strict_types=1);

namespace App\Api\Shared\Domain\ExtAddrobj\Repository;

use App\Api\Shared\Domain\ExtAddrobj\Entity\ExtAddrobj;
use App\Shared\Domain\Exception\EntityNotFoundException;

interface ExtAddrobjRepositoryInterface
{
    /**
     * @throws EntityNotFoundException
     */
    public function findOrFail(int $objectid): ExtAddrobj;

    public function persist(ExtAddrobj $extAddrobj): void;

    public function remove(ExtAddrobj $extAddrobj): void;
}
