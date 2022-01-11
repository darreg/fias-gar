<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\ParseTag;

use App\DataLoad\Application\UseCase\SaveTag\Command as SaveCommand;
use App\DataLoad\Domain\Tag\Service\TagParserInterface;
use App\Shared\Domain\Bus\Command\CommandBusInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use Exception;
use Psr\Log\LoggerInterface;

final class Handler implements CommandHandlerInterface
{
    private CommandBusInterface $commandBus;
    private TagParserInterface $parser;
    private LoggerInterface $parseSuccessLogger;
    private LoggerInterface $parseErrorsLogger;

    public function __construct(
        CommandBusInterface $commandBus,
        TagParserInterface $parser,
        LoggerInterface $parseSuccessLogger,
        LoggerInterface $parseErrorsLogger
    ) {
        $this->commandBus = $commandBus;
        $this->parser = $parser;
        $this->parseSuccessLogger = $parseSuccessLogger;
        $this->parseErrorsLogger = $parseErrorsLogger;
    }

    public function __invoke(Command $command): void
    {
        try {
            $data = $this->parser->parse($command->getTagSource());
            $this->parseSuccessLogger->info($command->getFileToken() . ' ; ' . $command->getTagSource());

            $this->commandBus->dispatch(new SaveCommand($command->getFileToken(), $data));
        } catch (Exception $e) {
            $this->parseErrorsLogger->info($command->getFileToken() . ' ; ' . $command->getTagSource() . ' ; ' . $e->getMessage());
        }
    }
}
