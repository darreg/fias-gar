<?php

declare(strict_types=1);

namespace App\DataLoad\Application\Service;

interface SaverInterface
{
    /**
     * @param array<string, string> $values
     */
    public function upsert(string $token, array $values): void;
}