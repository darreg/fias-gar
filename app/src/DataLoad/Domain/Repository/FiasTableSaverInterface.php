<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Repository;

interface FiasTableSaverInterface
{
    public function upsert(string $token, array $values): void;
}