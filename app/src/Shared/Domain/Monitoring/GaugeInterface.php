<?php

declare(strict_types=1);

namespace App\Shared\Domain\Monitoring;

interface GaugeInterface
{
    /**
     * @param list<string> $labels
     */
    public function set(int|float $value, array $labels = []): void;

    /**
     * @param list<string> $labels
     */
    public function inc(array $labels = []): void;

    /**
     * @param list<string> $labels
     */
    public function incBy(int|float $count, array $labels = []): void;

    /**
     * @param list<string> $labels
     */
    public function dec(array $labels = []): void;

    /**
     * @param list<string> $labels
     */
    public function decBy(int|float $count, array $labels = []): void;
}
