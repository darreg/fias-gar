<?php

declare(strict_types=1);

namespace App\Api\Shared\Domain\Entity\ExtAddrobj;

use App\Shared\Domain\Exception\EntityNotFoundException;

interface ExtAddrobjRepositoryInterface
{
    /**
     * @throws EntityNotFoundException
     */
    public function findOrFail(int $objectid): ExtAddrobj;

    public function add(ExtAddrobj $extAddrobj): void;

    public function remove(ExtAddrobj $extAddrobj): void;
}
