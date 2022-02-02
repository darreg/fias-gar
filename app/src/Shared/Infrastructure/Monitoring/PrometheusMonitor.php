<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Monitoring;

use App\Shared\Domain\Monitoring\CounterInterface;
use App\Shared\Domain\Monitoring\GaugeInterface;
use App\Shared\Domain\Monitoring\MonitorInterface;
use Prometheus\CollectorRegistry;
use Prometheus\Exception\MetricsRegistrationException;
use Prometheus\RenderTextFormat;
use Prometheus\Storage\Adapter;

/** @psalm-suppress MethodSignatureMismatch */
final class PrometheusMonitor implements MonitorInterface
{
    private CollectorRegistry $registry;
    private string $namespace;

    public function __construct(
        Adapter $adapter,
        string $namespace = ''
    ) {
        $this->registry = new CollectorRegistry($adapter);
        $this->namespace = self::fixSymbols($namespace);
    }

    /**
     * @param list<string> $labels
     * @throws MetricsRegistrationException
     */
    public function getCounter(string $name, string $help, array $labels = [], string $namespace = ''): CounterInterface
    {
        $namespace = $namespace === '' ? $this->namespace : $namespace;
        return new PrometheusCounter($this->registry->getOrRegisterCounter($namespace, $name, $help, $labels));
    }

    /**
     * @param list<string> $labels
     * @throws MetricsRegistrationException
     */
    public function getGauge(string $name, string $help, array $labels = [], string $namespace = ''): GaugeInterface
    {
        $namespace = $namespace === '' ? $this->namespace : $namespace;
        return new PrometheusGauge($this->registry->getOrRegisterGauge($namespace, $name, $help, $labels));
    }

    public function expose(): string
    {
        return (new RenderTextFormat())->render($this->registry->getMetricFamilySamples());
    }

    public function exposeContentType(): string
    {
        return RenderTextFormat::MIME_TYPE;
    }

    private static function fixSymbols(string $namespace): string
    {
        $namespace = str_replace('-', '_', $namespace);
        /** @var string $namespace */
        return preg_replace('/[^a-zA-Z0-9_]/', '', $namespace);
    }
}
