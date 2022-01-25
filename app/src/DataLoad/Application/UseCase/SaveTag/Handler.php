<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\SaveTag;

use App\DataLoad\Domain\Import\Entity\Import;
use App\DataLoad\Domain\Import\Service\ImportCounterIncrementorInterface;
use App\DataLoad\Domain\Tag\Service\TagSaverInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use Exception;
use Psr\Log\LoggerInterface;

final class Handler implements CommandHandlerInterface
{
    private TagSaverInterface $saver;
    private ImportCounterIncrementorInterface $incrementor;
    private LoggerInterface $saveErrorsLogger;

    public function __construct(
        TagSaverInterface $saver,
        ImportCounterIncrementorInterface $incrementor,
        LoggerInterface $saveErrorsLogger
    ) {
        $this->saver = $saver;
        $this->incrementor = $incrementor;
        $this->saveErrorsLogger = $saveErrorsLogger;
    }

    public function __invoke(Command $command): void
    {
        try {
            $this->saver->upsert($command->getFileToken(), $command->getValues());
            $this->incrementor->inc(
                $command->getType(),
                $command->getVersionId(),
                Import::COUNTER_FIELD_SAVE_SUCCESS_NUM
            );
        } catch (Exception $e) {
            $this->saveErrorsLogger->info(
                $command->getVersionId() . ';' .
                $command->getFileToken() . ' ; ' .
                serialize($command->getValues()) . ' ; ' .
                $e->getMessage()
            );
            $this->incrementor->inc(
                $command->getType(),
                $command->getVersionId(),
                Import::COUNTER_FIELD_SAVE_ERROR_NUM
            );
        }
    }
}
