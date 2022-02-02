<?php

declare(strict_types=1);

namespace App\Shared\Domain\Monitoring;

interface CounterInterface
{
    /**
     * @param list<string> $labels
     */
    public function inc(array $labels = []): void;

    /**
     * @param list<string> $labels
     */
    public function incBy(int|float $count, array $labels = []): void;
}
