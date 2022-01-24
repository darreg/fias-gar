<?php

declare(strict_types=1);

namespace App\DataLoad\Presentation\Command;

use App\Shared\Infrastructure\Doctrine\ViewRefresher;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class RefreshViewsCommand extends Command
{
    private LoggerInterface $logger;
    private ViewRefresher $viewRefresher;

    /**
     * @param list<string> $importTokens
     */
    public function __construct(
        ViewRefresher $viewRefresher,
        LoggerInterface $deltaImportLogger
    ) {
        parent::__construct();
        $this->viewRefresher = $viewRefresher;
        $this->logger = $deltaImportLogger;
    }

    protected function configure(): void
    {
        $this
            ->setName('fias:refresh_view')
            ->setDescription('Refresh materialized views');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->showStartMessage($output);

        try {
            $this->viewRefresher->refresh('v_addrobj_adm');
            $this->viewRefresher->refresh('v_addrobj_mun');
            $this->viewRefresher->refresh('v_addrobj_plain_adm');
            $this->viewRefresher->refresh('v_addrobj_plain_mun');
            $this->viewRefresher->refresh('v_addrobj_names');
            $this->viewRefresher->refresh('v_addrobj_address_strings');
            $this->viewRefresher->refresh('v_search_types');
            $this->viewRefresher->refresh('v_search_addrobjects');
            $this->viewRefresher->refresh('v_search_houses');
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
        $output->writeln('Refresh materialized views');
        $output->writeln('Getting started : ' . date('d.m.Y H:i:s'));
    }

    private function showFinishMessage(OutputInterface $output): void
    {
        $output->writeln('End of work : ' . date('d.m.Y H:i:s'));
        $output->writeln('=====');
    }
}
