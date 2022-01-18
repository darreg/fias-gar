<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Counter\Repository;

use App\DataLoad\Domain\Counter\Entity\Counter;
use App\Shared\Domain\Exception\EntityNotFoundException;

interface CounterRepositoryInterface
{
    public function find(string $key): ?Counter;

    /**
     * @throws EntityNotFoundException
     */
    public function findOrFail(string $key): Counter;

    /**
     * @return array<int, Counter>
     */
    public function findAll(): array;

    public function persist(Counter $counter): void;

    public function remove(Counter $counter): void;
}
