<?php

declare(strict_types=1);

namespace App\DataLoad\Presentation\Command;

use App\DataLoad\Infrastructure\Service\CompletedImportSaver;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class SaveCompletedImportCommand extends Command
{
    private CompletedImportSaver $completedImportSaver;
    private LoggerInterface $logger;

    /**
     * @param list<string> $importTokens
     */
    public function __construct(
        CompletedImportSaver $completedImportSaver,
        LoggerInterface $deltaImportLogger
    ) {
        parent::__construct();

        $this->completedImportSaver = $completedImportSaver;
        $this->logger = $deltaImportLogger;
    }

    protected function configure(): void
    {
        $this
            ->setName('fias:save-completed-imports')
            ->setDescription('Save completed imports to database');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->showStartMessage($output);

        try {
            $this->completedImportSaver->save();
        } catch (Exception $e) {
            $this->logger->error(
                $e->getMessage() . ' ; ' . $e->getFile() . ' ; ' . $e->getLine(),
                [$e->getPrevious()]
            );
            return Command::FAILURE;
        }

        $this->showFinishMessage($output);

        return Command::SUCCESS;
    }

    private function showStartMessage(OutputInterface $output): void
    {
        $output->writeln('=====');
        $output->writeln('Save completed imports to database');
        $output->writeln('Getting started : ' . date('d.m.Y H:i:s'));
    }

    private function showFinishMessage(OutputInterface $output): void
    {
        $output->writeln('End of work : ' . date('d.m.Y H:i:s'));
        $output->writeln('=====');
    }
}
