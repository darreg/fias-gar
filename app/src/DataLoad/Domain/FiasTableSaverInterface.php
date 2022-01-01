<?php

declare(strict_types=1);

namespace App\DataLoad\Domain;

interface FiasTableSaverInterface
{
    public function upsert(string $token, array $values): void;
}
