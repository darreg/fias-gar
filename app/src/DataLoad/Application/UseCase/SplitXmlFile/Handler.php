<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\SplitXmlFile;

use App\DataLoad\Application\UseCase\ParseTag\Command as ParseCommand;
use App\DataLoad\Domain\Import\Entity\Import;
use App\DataLoad\Domain\Import\Service\ImportCounterIncrementorInterface;
use App\DataLoad\Domain\Tag\Service\TagGeneratorInterface;
use App\Shared\Domain\Bus\Command\CommandBusInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use Exception;
use Psr\Log\LoggerInterface;

final class Handler implements CommandHandlerInterface
{
    private TagGeneratorInterface $tagGenerator;
    private CommandBusInterface $commandBus;
    private ImportCounterIncrementorInterface $incrementor;
    private LoggerInterface $splitErrorsLogger;

    public function __construct(
        TagGeneratorInterface             $tagGenerator,
        CommandBusInterface               $commandBus,
        ImportCounterIncrementorInterface $incrementor,
        LoggerInterface                   $splitErrorsLogger
    ) {
        $this->tagGenerator = $tagGenerator;
        $this->commandBus = $commandBus;
        $this->incrementor = $incrementor;
        $this->splitErrorsLogger = $splitErrorsLogger;
    }

    public function __invoke(Command $command): void
    {
        $type = $command->getType();
        $versionId = $command->getVersionId();
        $fileToken = $command->getFileToken();

        try {
            $tagSources = $this->tagGenerator->generate($command->getFilePath(), $command->getTagName());
            foreach ($tagSources as $tagSource) {
                $this->commandBus->dispatch(new ParseCommand($type, $versionId, $fileToken, $tagSource));
                $this->incrementor->inc($type, $versionId, Import::COUNTER_FIELD_TASK_NUM);
            }
        } catch (Exception $e) {
            $this->splitErrorsLogger->info(
                $command->getFilePath() . ' ; ' .
                $command->getTagName() . ' ; ' .
                $e->getMessage()
            );
        }
    }
}
