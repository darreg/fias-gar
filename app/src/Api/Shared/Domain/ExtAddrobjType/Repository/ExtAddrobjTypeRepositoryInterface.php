<?php

declare(strict_types=1);

namespace App\Api\Shared\Domain\ExtAddrobjType\Repository;

use App\Api\Shared\Domain\ExtAddrobjType\Entity\ExtAddrobjType;
use App\Api\Shared\Domain\ExtAddrobjType\Entity\Id;
use App\Shared\Domain\Exception\EntityNotFoundException;

interface ExtAddrobjTypeRepositoryInterface
{
    /**
     * @throws EntityNotFoundException
     */
    public function findOrFail(Id $id): ExtAddrobjType;

    public function persist(ExtAddrobjType $extAddHouseType): void;

    public function remove(ExtAddrobjType $extAddHouseType): void;
}
