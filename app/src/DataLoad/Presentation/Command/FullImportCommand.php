<?php

declare(strict_types=1);

namespace App\DataLoad\Presentation\Command;

use App\DataLoad\Application\UseCase\DownloadXmlFiles\Command as DownloadCommand;
use App\DataLoad\Application\UseCase\ImportXmlFiles\Command as ImportCommand;
use App\DataLoad\Application\UseCase\MarkLoaded\Command as MarkLoadedCommand;
use App\DataLoad\Application\UseCase\NextVersion\Query as NextVersionQuery;
use App\DataLoad\Application\UseCase\NextVersion\Response as NextVersionResponse;
use App\DataLoad\Domain\Version\Entity\Version;
use App\Shared\Domain\Bus\Command\CommandBusInterface;
use App\Shared\Domain\Bus\Query\QueryBusInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class FullImportCommand extends Command
{
    private CommandBusInterface $commandBus;
    private QueryBusInterface $queryBus;
    private LoggerInterface $logger;
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
        LoggerInterface $fullImportLogger,
        array $importTokens
    ) {
        parent::__construct();
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
        $this->logger = $fullImportLogger;
        $this->importTokens = $importTokens;
    }

    protected function configure(): void
    {
        $this
            ->setName('fias:import:full')
            ->setDescription('Downloading and importing full database')
            ->setHelp('fias:import:full VERSION')
            ->addArgument('version', InputArgument::OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $versionId = $this->getVersionId($input);
        if ($versionId === null) {
            $output->writeln('There is no relevant version to download');
            return Command::FAILURE;
        }

        $this->showStartMessage($output, $versionId);

        try {
            $output->writeln('- Downloading zip');
            $this->commandBus->dispatch(new DownloadCommand($versionId, Version::TYPE_FULL));

            $output->writeln('- Filling the xml import queue');
            /** @psalm-suppress MixedArgumentTypeCoercion */
            $this->commandBus->dispatch(new ImportCommand(Version::TYPE_FULL, $versionId, $this->importTokens));

            $output->writeln('- Mark all previous delta version as covered');
            $this->commandBus->dispatch(new MarkLoadedCommand(Version::TYPE_FULL, $versionId));
        } catch (Exception $e) {
            $this->logger->error(
                $versionId . ' ; ' . $e->getMessage() . ' ; ' . $e->getFile() . ' ; ' . $e->getLine(),
                [$e->getPrevious()]
            );
            return Command::FAILURE;
        }

        $this->showFinishMessage($output);

        return Command::SUCCESS;
    }

    private function getVersionId(InputInterface $input): ?string
    {
        $versionId = $input->getArgument('version');
        if ($versionId === null) {
            /** @var NextVersionResponse $response */
            $response = $this->queryBus->ask(new NextVersionQuery(Version::TYPE_FULL));
            $versionId = $response->answer();
        }

        return $versionId;
    }

    private function showStartMessage(OutputInterface $output, string $versionId): void
    {
        $output->writeln('=====');
        $output->writeln("Downloading and importing full database version '{$versionId}'");
        $output->writeln('Getting started : ' . date('d.m.Y H:i:s'));
    }

    private function showFinishMessage(OutputInterface $output): void
    {
        $output->writeln('End of work : ' . date('d.m.Y H:i:s'));
        $output->writeln('=====');
    }
}
