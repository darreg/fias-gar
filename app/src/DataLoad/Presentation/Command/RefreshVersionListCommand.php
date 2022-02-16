<?php

declare(strict_types=1);

namespace App\DataLoad\Presentation\Command;

use App\DataLoad\Application\UseCase\RefreshVersion\Command as RefreshVersionCommand;
use App\Shared\Domain\Bus\Command\CommandBusInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class RefreshVersionListCommand extends Command
{
    private CommandBusInterface $commandBus;
    private LoggerInterface $logger;

    public function __construct(
        CommandBusInterface $commandBus,
        LoggerInterface $deltaImportLogger
    ) {
        parent::__construct();
        $this->commandBus = $commandBus;
        $this->logger = $deltaImportLogger;
    }

    protected function configure(): void
    {
        $this
            ->setName('fias:refresh-version-list')
            ->setDescription('Refresh version list');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->showStartMessage($output);

        try {
            $this->commandBus->dispatch(new RefreshVersionCommand());
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
        $output->writeln('Refresh version list');
        $output->writeln('Getting started : ' . date('d.m.Y H:i:s'));
    }

    private function showFinishMessage(OutputInterface $output): void
    {
        $output->writeln('End of work : ' . date('d.m.Y H:i:s'));
        $output->writeln('=====');
    }
}
