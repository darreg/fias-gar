<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\SplitXmlFile;

use App\DataLoad\Application\Service\QueueLimiterInterface;
use App\DataLoad\Application\UseCase\ParseTag\Command as ParseCommand;
use App\DataLoad\Domain\Import\Entity\Import;
use App\DataLoad\Domain\Import\Service\ImportCounterIncrementorInterface;
use App\DataLoad\Domain\Tag\Service\TagGeneratorInterface;
use App\DataLoad\Domain\Version\Entity\Version;
use App\Shared\Domain\Bus\Command\CommandBusInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use Exception;
use Psr\Log\LoggerInterface;

final class Handler implements CommandHandlerInterface
{
    private TagGeneratorInterface $tagGenerator;
    private CommandBusInterface $commandBus;
    private ImportCounterIncrementorInterface $incrementor;
    private QueueLimiterInterface $queueLimiter;
    private LoggerInterface $splitErrorsLogger;

    public function __construct(
        TagGeneratorInterface $tagGenerator,
        CommandBusInterface $commandBus,
        ImportCounterIncrementorInterface $incrementor,
        QueueLimiterInterface $queueLimiter,
        LoggerInterface $splitErrorsLogger
    ) {
        $this->tagGenerator = $tagGenerator;
        $this->commandBus = $commandBus;
        $this->incrementor = $incrementor;
        $this->queueLimiter = $queueLimiter;
        $this->splitErrorsLogger = $splitErrorsLogger;
    }

    public function __invoke(Command $command): void
    {
        /** @var Version::TYPE_* $type */
        $type = $command->getType();
        $versionId = $command->getVersionId();
        $fileToken = $command->getFileToken();
        $filePath = $command->getFilePath();
        $tagName = $command->getTagName();

        try {
            $tagSources = $this->tagGenerator->generate($filePath, $tagName);
            foreach ($tagSources as $tagSource) {
                $this->commandBus->dispatch(new ParseCommand($type, $versionId, $fileToken, $tagSource));
                $this->incrementor->inc($type, $versionId, Import::COUNTER_FIELD_PARSE_TASK_NUM);
                $this->queueLimiter->makePause(QueueLimiterInterface::QUEUE_PARSE, $type, $versionId);
            }
        } catch (Exception $e) {
            $this->splitErrorsLogger->info($filePath . ' ; ' . $tagName . ' ; ' . $e->getMessage());
        }
    }
}
