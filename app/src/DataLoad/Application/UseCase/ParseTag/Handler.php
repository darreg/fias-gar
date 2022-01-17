<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\ParseTag;

use App\DataLoad\Application\UseCase\SaveTag\Command as SaveCommand;
use App\DataLoad\Domain\Counter\Entity\Counter;
use App\DataLoad\Domain\Counter\Service\CounterIncrementorInterface;
use App\DataLoad\Domain\Tag\Service\TagParserInterface;
use App\Shared\Domain\Bus\Command\CommandBusInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use Exception;
use Psr\Log\LoggerInterface;

final class Handler implements CommandHandlerInterface
{
    private CommandBusInterface $commandBus;
    private TagParserInterface $parser;
    private CounterIncrementorInterface $counter;
    private LoggerInterface $parseErrorsLogger;

    public function __construct(
        CommandBusInterface $commandBus,
        TagParserInterface $parser,
        CounterIncrementorInterface $counter,
        LoggerInterface $parseErrorsLogger
    ) {
        $this->commandBus = $commandBus;
        $this->parser = $parser;
        $this->counter = $counter;
        $this->parseErrorsLogger = $parseErrorsLogger;
    }

    public function __invoke(Command $command): void
    {
        try {
            $data = $this->parser->parse($command->getTagSource());
            $data[SaveCommand::FIELD_NAME_CHANGED_AT] = date('Y-m-d H:i:s');

            $this->commandBus->dispatch(
                new SaveCommand(
                    $command->getType(),
                    $command->getVersionId(),
                    $command->getFileToken(),
                    $data
                )
            );
            $this->counter->inc(
                $command->getType(),
                $command->getVersionId(),
                Counter::COUNTER_FIELD_PARSE_SUCCESS_NUM
            );
        } catch (Exception $e) {
            $this->parseErrorsLogger->info(
                $command->getVersionId() . ';' .
                $command->getFileToken() . ' ; ' .
                $command->getTagSource() . ' ; ' .
                $e->getMessage()
            );
            $this->counter->inc(
                $command->getType(),
                $command->getVersionId(),
                Counter::COUNTER_FIELD_PARSE_ERROR_NUM
            );
        }
    }
}
