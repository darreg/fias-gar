<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\SaveTag;

use App\DataLoad\Domain\FiasTableSaverInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use Exception;
use Psr\Log\LoggerInterface;

class Handler implements CommandHandlerInterface
{
    private FiasTableSaverInterface $fiasTableSaver;
    private LoggerInterface $saveSuccessLogger;
    private LoggerInterface $saveErrorsLogger;

    public function __construct(
        FiasTableSaverInterface $fiasTableSaver,
        LoggerInterface $saveSuccessLogger,
        LoggerInterface $saveErrorsLogger
    )
    {
        $this->fiasTableSaver = $fiasTableSaver;
        $this->saveSuccessLogger = $saveSuccessLogger;
        $this->saveErrorsLogger = $saveErrorsLogger;
    }

    public function __invoke(Command $command): bool
    {
        try {
            $this->fiasTableSaver->upsert($command->getFileToken(), $command->getValues());
            $this->saveSuccessLogger->info($command->getFileToken() . ' ; ' . serialize($command->getValues()) );
            return true;
        } catch (Exception $e) {
            $this->saveErrorsLogger->info($command->getFileToken() . ' ; ' . serialize($command->getValues()) . ' ; ' . $e->getMessage());
            return false;
        }
    }
}
