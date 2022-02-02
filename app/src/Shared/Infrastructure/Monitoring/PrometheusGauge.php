<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Monitoring;

use App\Shared\Domain\Monitoring\GaugeInterface;
use Prometheus\Gauge;

/** @psalm-suppress MethodSignatureMismatch */
class PrometheusGauge implements GaugeInterface
{
    private Gauge $gauge;

    public function __construct(Gauge $gauge)
    {
        $this->gauge = $gauge;
    }

    /**
     * @param list<string> $labels
     */
    public function set(float|int $value, array $labels = []): void
    {
        $this->gauge->set($value, $labels);
    }

    /**
     * @param list<string> $labels
     */
    public function inc(array $labels = []): void
    {
        $this->gauge->inc($labels);
    }

    /**
     * @param list<string> $labels
     */
    public function incBy(float|int $count, array $labels = []): void
    {
        $this->gauge->incBy($count, $labels);
    }

    /**
     * @param list<string> $labels
     */
    public function dec(array $labels = []): void
    {
        $this->gauge->dec($labels);
    }

    /**
     * @param list<string> $labels
     */
    public function decBy(float|int $count, array $labels = []): void
    {
        $this->gauge->decBy($count, $labels);
    }
}
