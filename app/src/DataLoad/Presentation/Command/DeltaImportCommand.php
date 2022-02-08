<?php

declare(strict_types=1);

namespace App\DataLoad\Presentation\Command;

use App\DataLoad\Domain\Version\Entity\Version;
use App\DataLoad\Infrastructure\Service\ImportCommandService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class DeltaImportCommand extends Command
{
    private ImportCommandService $importCommandService;
    private LoggerInterface $logger;

    public function __construct(
        ImportCommandService $importCommandService,
        LoggerInterface $deltaImportLogger
    ) {
        parent::__construct();
        $this->importCommandService = $importCommandService;
        $this->logger = $deltaImportLogger;
    }

    protected function configure(): void
    {
        $this
            ->setName('fias:import:delta')
            ->setDescription('Download and import FIAS delta database')
            ->setHelp('fias:import:delta VERSION_ID')
            ->addArgument('versionId', InputArgument::OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($this->importCommandService->importIsNotPossible()) {
            $output->writeln('<fg=red>There are incomplete imports. Wait for them to complete</>');
            return Command::FAILURE;
        }

        $this->importCommandService->refreshVersionList();

        $versionId =
            $input->getArgument('versionId') ??
            $this->importCommandService->getNextVersionId(Version::TYPE_DELTA);

        if ($versionId === null) {
            $output->writeln('There is no relevant version to download');
            return Command::FAILURE;
        }

        if (!$this->importCommandService->isValidVersion($versionId)) {
            $output->writeln('This version is invalid');
            return Command::FAILURE;
        }

        $this->importCommandService->initMonitoring(Version::TYPE_DELTA, $versionId);

        $this->showStartMessage($output, $versionId);

        try {
            $output->writeln('- Downloading');
            $this->importCommandService->download(Version::TYPE_DELTA, $versionId);

            $output->writeln('- Extracting');
            $this->importCommandService->extract(Version::TYPE_DELTA, $versionId);

            $output->writeln('- Filling import queue');
            $this->importCommandService->fillImportQueue(Version::TYPE_DELTA, $versionId);

            $output->writeln('- Mark this version as loaded');
            $this->importCommandService->markAsLoaded(Version::TYPE_DELTA, $versionId);
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

    private function showStartMessage(OutputInterface $output, string $versionId): void
    {
        $output->writeln('=====');
        $output->writeln("Downloading and importing delta version '{$versionId}'");
        $output->writeln('Getting started : ' . date('d.m.Y H:i:s'));
    }

    private function showFinishMessage(OutputInterface $output): void
    {
        $output->writeln('End of work : ' . date('d.m.Y H:i:s'));
        $output->writeln('=====');
    }
}
