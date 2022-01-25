<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Version\Repository;

use App\DataLoad\Domain\Version\Entity\Version;
use App\Shared\Domain\Exception\EntityNotFoundException;

interface VersionRepositoryInterface
{
    public function find(string $id): ?Version;

    /**
     * @throws EntityNotFoundException
     */
    public function findOrFail(string $id): Version;

    public function persist(Version $version): void;

    public function remove(Version $version): void;
}
