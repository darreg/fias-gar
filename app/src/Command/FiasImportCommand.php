<?php

namespace App\Command;

use App\Service\FiasImportService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\LockableTrait;

final class FiasImportCommand extends Command
{
//    use LockableTrait;

    private FiasImportService $fiasImportService;
    private LoggerInterface $logger;

    public function __construct(
        FiasImportService $fiasImportService,
        LoggerInterface $logger
    ) {
        parent::__construct();
        $this->fiasImportService = $fiasImportService;
        $this->logger = $logger;
    }

    protected function configure(): void
    {
        $this
            ->setName('fias:import')
            ->setDescription('Импорт загруженного ранее xml-файла')
            ->setHelp('fias:import FILENAME.XML')
//            ->setHidden(true)
            ->addArgument('fileName', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $fileName = $input->getArgument('fileName');

        $startTime = time();
        $output->writeln('=====');
        $output->writeln('Импорт файла ' . $fileName . ' : ' .  date('d.m.Y H:i:s', $startTime));

        try {
            $num = $this->fiasImportService->import($fileName);
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage() . ' ; ' . $e->getFile() . ' ; ' . $e->getLine());
            return Command::FAILURE;
        }

        $output->writeln('-----');
        $output->writeln('<info> - всего задач в очереди: ' . $num . '</info>');
        $output->writeln('=====');
        return Command::SUCCESS;
    }
}
