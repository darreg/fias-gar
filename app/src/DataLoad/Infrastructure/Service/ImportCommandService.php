<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Application\UseCase\Download\Command as DownloadCommand;
use App\DataLoad\Application\UseCase\Extract\Command as ExtractCommand;
use App\DataLoad\Application\UseCase\ImportXmlFiles\Command as ImportCommand;
use App\DataLoad\Application\UseCase\MarkLoaded\Command as MarkLoadedCommand;
use App\DataLoad\Application\UseCase\NextVersion\Query as NextVersionQuery;
use App\DataLoad\Application\UseCase\NextVersion\Response as NextVersionResponse;
use App\DataLoad\Application\UseCase\RefreshVersion\Command as RefreshVersionCommand;
use App\DataLoad\Application\UseCase\ValidateVersion\Query as ValidateVersionQuery;
use App\DataLoad\Application\UseCase\ValidateVersion\Response as ValidateVersionResponse;
use App\DataLoad\Domain\Import\Entity\Import;
use App\DataLoad\Domain\Import\Repository\ImportFetcherInterface;
use App\DataLoad\Domain\Version\Entity\Version;
use App\Shared\Domain\Bus\Command\CommandBusInterface;
use App\Shared\Domain\Bus\Query\QueryBusInterface;
use App\Shared\Domain\Monitoring\MonitorInterface;

class ImportCommandService
{
    public const LOAD_VERSION_GAUGE_NAME = 'load_version';

    private CommandBusInterface $commandBus;
    private QueryBusInterface $queryBus;
    private ImportFetcherInterface $importFetcher;
    private MonitorInterface $monitor;
    /**
     * @param list<string> $importTokens
     */
    private array $importTokens;

    /**
     * @param list<string> $importTokens
     */
    public function __construct(
        CommandBusInterface $commandBus,
        QueryBusInterface $queryBus,
        ImportFetcherInterface $importFetcher,
        MonitorInterface $monitor,
        array $importTokens
    ) {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
        $this->importFetcher = $importFetcher;
        $this->monitor = $monitor;
        $this->importTokens = $importTokens;
    }

    public function importIsNotPossible(): bool
    {
        return $this->importFetcher->isUncompletedExists();
    }

    /**
     * @param Version::TYPE_* $type
     */
    public function download(string $type, string $versionId): void
    {
        $this->commandBus->dispatch(new DownloadCommand($type, $versionId));
    }

    /**
     * @param Version::TYPE_* $type
     */
    public function extract(string $type, string $versionId): void
    {
        $this->commandBus->dispatch(new ExtractCommand($type, $versionId));
    }

    /**
     * @param Version::TYPE_* $type
     */
    public function fillImportQueue(string $type, string $versionId): void
    {
        /** @psalm-suppress MixedArgumentTypeCoercion */
        $this->commandBus->dispatch(new ImportCommand($type, $versionId, $this->importTokens));
    }

    /**
     * @param Version::TYPE_* $type
     */
    public function markAsLoaded(string $type, string $versionId): void
    {
        $this->commandBus->dispatch(new MarkLoadedCommand($type, $versionId));
    }

    /**
     * @param Version::TYPE_* $type
     */
    public function getNextVersionId(string $type): ?string
    {
        /** @var NextVersionResponse $response */
        $response = $this->queryBus->ask(new NextVersionQuery($type));
        return $response->answer();
    }

    public function refreshVersionList(): void
    {
        $this->commandBus->dispatch(new RefreshVersionCommand());
    }

    public function isValidVersion(string $versionId): bool
    {
        /** @var ValidateVersionResponse $response */
        $response = $this->queryBus->ask(new ValidateVersionQuery(Version::TYPE_DELTA, $versionId));
        return $response->answer();
    }

    /**
     * @param Version::TYPE_* $type
     */
    public function initMonitoring(string $type, string $versionId): void
    {
        $this->monitor
            ->getGauge(self::LOAD_VERSION_GAUGE_NAME, '', ['type'])->set((int)$versionId, [$type]);
        $this->monitor
            ->getCounter(Import::COUNTER_FIELD_PARSE_ERROR_NUM, '', ['type', 'version'])
            ->incBy(0, [$type, $versionId]);
        $this->monitor
            ->getCounter(Import::COUNTER_FIELD_SAVE_ERROR_NUM, '', ['type', 'version'])
            ->incBy(0, [$type, $versionId]);
    }
}
