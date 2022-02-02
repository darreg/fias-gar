<?php

declare(strict_types=1);

namespace App\Shared\Domain\Monitoring;

interface MonitorInterface
{
    /**
     * @param list<string> $labels
     */
    public function getCounter(string $name, string $help, array $labels = [], string $namespace = ''): CounterInterface;

    /**
     * @param list<string> $labels
     */
    public function getGauge(string $name, string $help, array $labels = [], string $namespace = ''): GaugeInterface;

    public function expose(): string;

    public function exposeContentType(): string;
}
