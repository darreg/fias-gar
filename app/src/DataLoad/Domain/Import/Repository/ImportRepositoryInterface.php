<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Import\Repository;

use App\DataLoad\Domain\Import\Entity\Import;
use App\Shared\Domain\Exception\EntityNotFoundException;

interface ImportRepositoryInterface
{
    public function find(string $key): ?Import;

    /**
     * @throws EntityNotFoundException
     */
    public function findOrFail(string $key): Import;

    /**
     * @return array<int, Import>
     */
    public function findAll(): array;

    public function persist(Import $import): void;

    public function remove(Import $import): void;
}
