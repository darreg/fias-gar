<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Monitoring;

use App\Shared\Domain\Monitoring\CounterInterface;
use Prometheus\Counter;

/** @psalm-suppress MethodSignatureMismatch */
class PrometheusCounter implements CounterInterface
{
    private Counter $counter;

    public function __construct(Counter $counter)
    {
        $this->counter = $counter;
    }

    /**
     * @param list<string> $labels
     */
    public function inc(array $labels = []): void
    {
        $this->counter->inc($labels);
    }

    /**
     * @param list<string> $labels
     */
    public function incBy(float|int $count, array $labels = []): void
    {
        $this->counter->incBy($count, $labels);
    }
}
