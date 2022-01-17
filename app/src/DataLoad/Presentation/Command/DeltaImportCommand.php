<?php

namespace App\DataLoad\Presentation\Command;

use App\DataLoad\Application\Service\XmlDownloaderInterface;
use App\DataLoad\Application\UseCase\DownloadXmlFiles\Command as DownloadCommand;
use App\DataLoad\Application\UseCase\ImportXmlFiles\Command as ImportCommand;
use App\Shared\Domain\Bus\Command\CommandBusInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class DeltaImportCommand extends Command
{
    private CommandBusInterface $commandBus;
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
        LoggerInterface $deltaImportLogger,
        array $importTokens
    ) {
        parent::__construct();
        $this->commandBus = $commandBus;
        $this->logger = $deltaImportLogger;
        $this->importTokens = $importTokens;
    }

    protected function configure(): void
    {
        $this
            ->setName('fias:import:delta')
            ->setDescription('Скачивание и импорт изменений базы ФИАС')
            ->setHelp('fias:import:delta VERSION')
            ->addArgument('version', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $versionId = $input->getArgument('version');

        $startTime = time();
        $output->writeln('=====');
        $output->writeln("Скачивание и импорт изменений версии '{$versionId}'");
        $output->writeln('Начало работы : ' . date('d.m.Y H:i:s', $startTime));

        try {
            $output->writeln('Скачиваем zip-файл');
            $this->commandBus->dispatch(new DownloadCommand($versionId, XmlDownloaderInterface::TYPE_DELTA));

            $output->writeln('Заполняем очередь импорта xml-файлов');
            /** @psalm-suppress MixedArgumentTypeCoercion */
            $this->commandBus->dispatch(new ImportCommand($versionId, $this->importTokens));
        } catch (Exception $e) {
            $this->logger->error($versionId . ' ; ' . $e->getMessage() . ' ; ' . $e->getFile() . ' ; ' . $e->getLine(), [$e->getPrevious()]);
            return Command::FAILURE;
        }

        $output->writeln('Окончание работы : ' . date('d.m.Y H:i:s'));
        $output->writeln('=====');

        return Command::SUCCESS;
    }
}
