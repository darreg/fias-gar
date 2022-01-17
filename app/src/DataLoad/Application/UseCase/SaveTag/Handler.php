<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\SaveTag;

use App\DataLoad\Domain\Counter\Entity\Counter;
use App\DataLoad\Domain\Counter\Service\CounterIncrementorInterface;
use App\DataLoad\Domain\Tag\Service\TagSaverInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use Exception;
use Psr\Log\LoggerInterface;

final class Handler implements CommandHandlerInterface
{
    private TagSaverInterface $saver;
    private CounterIncrementorInterface $counter;
    private LoggerInterface $saveErrorsLogger;

    public function __construct(
        TagSaverInterface $saver,
        CounterIncrementorInterface $counter,
        LoggerInterface $saveErrorsLogger
    ) {
        $this->saver = $saver;
        $this->counter = $counter;
        $this->saveErrorsLogger = $saveErrorsLogger;
    }

    public function __invoke(Command $command): void
    {
        try {
            $this->saver->upsert($command->getFileToken(), $command->getValues());
            $this->counter->inc(
                $command->getType(),
                $command->getVersionId(),
                Counter::COUNTER_FIELD_SAVE_SUCCESS_NUM
            );
        } catch (Exception $e) {
            $this->saveErrorsLogger->info(
                $command->getVersionId() . ';' .
                $command->getFileToken() . ' ; ' .
                serialize($command->getValues()) . ' ; ' .
                $e->getMessage()
            );
            $this->counter->inc(
                $command->getType(),
                $command->getVersionId(),
                Counter::COUNTER_FIELD_SAVE_ERROR_NUM
            );
        }
    }
}
