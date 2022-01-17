<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\SplitXmlFile;

use App\DataLoad\Application\UseCase\ParseTag\Command as ParseCommand;
use App\DataLoad\Domain\Tag\Service\TagGeneratorInterface;
use App\Shared\Domain\Bus\Command\CommandBusInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use Exception;
use Psr\Log\LoggerInterface;

final class Handler implements CommandHandlerInterface
{
    private LoggerInterface $splitErrorsLogger;

    private TagGeneratorInterface $tagGenerator;
    private CommandBusInterface $commandBus;

    public function __construct(
        TagGeneratorInterface $tagGenerator,
        CommandBusInterface $commandBus,
        LoggerInterface $splitErrorsLogger
    ) {
        $this->splitErrorsLogger = $splitErrorsLogger;
        $this->tagGenerator = $tagGenerator;
        $this->commandBus = $commandBus;
    }

    public function __invoke(Command $command): void
    {
        $fileToken = $command->getFileToken();
        $versionId = $command->getVersionId();

        try {
            $tagSources = $this->tagGenerator->generate($command->getFilePath(), $command->getTagName());
            foreach ($tagSources as $tagSource) {
                $this->commandBus->dispatch(new ParseCommand($versionId, $fileToken, $tagSource));
            }
        } catch (Exception $e) {
            $this->splitErrorsLogger->info($command->getFilePath() . ' ; ' . $command->getTagName() . ' ; ' . $e->getMessage());
        }
    }
}
