<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\SaveTag;

use App\DataLoad\Domain\Tag\Service\TagSaverInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use Exception;
use Psr\Log\LoggerInterface;

final class Handler implements CommandHandlerInterface
{
    private TagSaverInterface $saver;
    private LoggerInterface $saveSuccessLogger;
    private LoggerInterface $saveErrorsLogger;

    public function __construct(
        TagSaverInterface $saver,
        LoggerInterface $saveSuccessLogger,
        LoggerInterface $saveErrorsLogger
    ) {
        $this->saver = $saver;
        $this->saveSuccessLogger = $saveSuccessLogger;
        $this->saveErrorsLogger = $saveErrorsLogger;
    }

    public function __invoke(Command $command): void
    {
        try {
            $this->saver->upsert($command->getFileToken(), $command->getValues());
            $this->saveSuccessLogger->info($command->getVersionId() . ';' . $command->getFileToken() . ' ; ' . serialize($command->getValues()));
        } catch (Exception $e) {
            $this->saveErrorsLogger->info($command->getVersionId() . ';' . $command->getFileToken() . ' ; ' . serialize($command->getValues()) . ' ; ' . $e->getMessage());
        }
    }
}
