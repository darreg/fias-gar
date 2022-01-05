<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\SaveTag;

use App\DataLoad\Domain\SaverInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use Exception;
use Psr\Log\LoggerInterface;

final class Handler implements CommandHandlerInterface
{
    private SaverInterface $saver;
    private LoggerInterface $saveSuccessLogger;
    private LoggerInterface $saveErrorsLogger;

    public function __construct(
        SaverInterface $saver,
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
            $this->saveSuccessLogger->info($command->getFileToken() . ' ; ' . serialize($command->getValues()));
        } catch (Exception $e) {
            $this->saveErrorsLogger->info($command->getFileToken() . ' ; ' . serialize($command->getValues()) . ' ; ' . $e->getMessage());
        }
    }
}
