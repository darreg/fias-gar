<?php

declare(strict_types=1);

namespace App\Api\Shared\Domain\ExtAddHouseType\Repository;

use App\Api\Shared\Domain\ExtAddHouseType\Entity\ExtAddHouseType;
use App\Api\Shared\Domain\ExtAddHouseType\Entity\Id;
use App\Shared\Domain\Exception\EntityNotFoundException;

interface ExtAddHouseTypeRepositoryInterface
{
    /**
     * @throws EntityNotFoundException
     */
    public function findOrFail(Id $id): ExtAddHouseType;

    public function persist(ExtAddHouseType $extAddHouseType): void;

    public function remove(ExtAddHouseType $extAddHouseType): void;
}
