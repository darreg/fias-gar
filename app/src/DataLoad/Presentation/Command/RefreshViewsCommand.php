<?php

declare(strict_types=1);

namespace App\DataLoad\Presentation\Command;

use App\DataLoad\Domain\Import\Repository\ImportFetcherInterface;
use App\DataLoad\Infrastructure\Service\ImportMarker;
use App\DataLoad\Infrastructure\Service\ImportRedisMarker;
use App\Shared\Infrastructure\Doctrine\ViewRefresher;
use DateTimeImmutable;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class RefreshViewsCommand extends Command
{
    private ViewRefresher $viewRefresher;
    private ImportFetcherInterface $importFinder;
    private ImportMarker $importMarker;
    private ImportRedisMarker $importRedisMarker;
    private LoggerInterface $logger;

    /**
     * @param list<string> $importTokens
     */
    public function __construct(
        ViewRefresher $viewRefresher,
        ImportFetcherInterface $importFetcher,
        ImportMarker $importMarker,
        ImportRedisMarker $importRedisMarker,
        LoggerInterface $deltaImportLogger
    ) {
        parent::__construct();
        $this->viewRefresher = $viewRefresher;
        $this->importFinder = $importFetcher;
        $this->importMarker = $importMarker;
        $this->importRedisMarker = $importRedisMarker;
        $this->logger = $deltaImportLogger;
    }

    protected function configure(): void
    {
        $this
            ->setName('fias:refresh-views')
            ->setDescription('Refresh materialized views');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($this->importFinder->isUncompletedExists()) {
            $output->writeln('<fg=red>There are incomplete imports. Wait for them to complete</>');
            return Command::FAILURE;
        }

        $this->showStartMessage($output);

        try {
            $output->writeln('- v_addrobj_adm');
            $this->viewRefresher->refresh('v_addrobj_adm');
            $output->writeln('- v_addrobj_mun');
            $this->viewRefresher->refresh('v_addrobj_mun');
            $output->writeln('- v_addrobj_plain_adm');
            $this->viewRefresher->refresh('v_addrobj_plain_adm');
            $output->writeln('- v_addrobj_plain_mun');
            $this->viewRefresher->refresh('v_addrobj_plain_mun');
            $output->writeln('- v_addrobj_names');
            $this->viewRefresher->refresh('v_addrobj_names');
            $output->writeln('- v_addrobj_address_strings');
            $this->viewRefresher->refresh('v_addrobj_address_strings');
            $output->writeln('- v_search_types');
            $this->viewRefresher->refresh('v_search_types');
            $output->writeln('- v_search_addrobjects');
            $this->viewRefresher->refresh('v_search_addrobjects');
            $output->writeln('- v_search_houses');
            $this->viewRefresher->refresh('v_search_houses');

            $now = new DateTimeImmutable();
            $this->importMarker->markViewsRefreshed($now);
            $this->importRedisMarker->markViewsRefreshed($now);
            
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
