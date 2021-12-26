<?php

declare(strict_types=1);

namespace App\Api\Shared\Domain\Entity\ExtAddrobj;

interface ExtAddrobjRepositoryInterface
{
    public function get(Id $id): ExtAddrobj;

    public function add(ExtAddrobj $extAddrobj): void;

    public function remove(ExtAddrobj $extAddrobj): void;
}
