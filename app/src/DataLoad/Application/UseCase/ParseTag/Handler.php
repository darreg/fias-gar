<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\ParseTag;

use App\DataLoad\Application\UseCase\SaveTag\Command as SaveCommand;
use App\DataLoad\Domain\Import\Entity\Import;
use App\DataLoad\Domain\Import\Service\ImportCounterIncrementorInterface;
use App\DataLoad\Domain\Tag\Service\TagParserInterface;
use App\Shared\Domain\Bus\Command\CommandBusInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use DateTimeImmutable;
use Exception;
use Psr\Log\LoggerInterface;

final class Handler implements CommandHandlerInterface
{
    private CommandBusInterface $commandBus;
    private TagParserInterface $parser;
    private ImportCounterIncrementorInterface $incrementor;
    private LoggerInterface $parseErrorsLogger;

    public function __construct(
        CommandBusInterface $commandBus,
        TagParserInterface $parser,
        ImportCounterIncrementorInterface $incrementor,
        LoggerInterface $parseErrorsLogger
    ) {
        $this->commandBus = $commandBus;
        $this->parser = $parser;
        $this->incrementor = $incrementor;
        $this->parseErrorsLogger = $parseErrorsLogger;
    }

    public function __invoke(Command $command): void
    {
        $type = $command->getType();
        $versionId = $command->getVersionId();
        $tagSource = $command->getTagSource();
        $fileToken = $command->getFileToken();

        try {
            $data = $this->parser->parse($tagSource);

            $data[SaveCommand::FIELD_NAME_CHANGED_AT] =
                (new DateTimeImmutable())->format(SaveCommand::DATE_FORMAT);

            $this->commandBus->dispatch(new SaveCommand($type, $versionId, $fileToken, $data));
            $this->incrementor->inc($type, $versionId, Import::COUNTER_FIELD_PARSE_SUCCESS_NUM);
            $this->incrementor->inc($type, $versionId, Import::COUNTER_FIELD_SAVE_TASK_NUM);
        } catch (Exception $e) {
            $this->parseErrorsLogger->info(
                $versionId . ';' . $fileToken . ' ; ' . $tagSource . ' ; ' . $e->getMessage()
            );
            $this->incrementor->inc($type, $versionId, Import::COUNTER_FIELD_PARSE_ERROR_NUM);
        }
    }
}
