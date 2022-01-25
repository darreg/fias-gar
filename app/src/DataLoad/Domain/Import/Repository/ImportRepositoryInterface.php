<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Import\Repository;

use App\DataLoad\Domain\Import\Entity\Import;
use App\Shared\Domain\Exception\EntityNotFoundException;

interface ImportRepositoryInterface
{
    public function find(string $type, string $versionId): ?Import;

    /**
     * @throws EntityNotFoundException
     */
    public function findOrFail(string $type, string $versionId): Import;

    public function persist(Import $import): void;

    public function remove(Import $import): void;
}
