<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\ParseTag;

use App\DataLoad\Application\UseCase\SaveTag\Command as SaveCommand;
use App\Shared\Domain\Bus\Command\CommandBusInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use DomainException;
use Exception;
use Psr\Log\LoggerInterface;
use function Lambdish\Phunctional\reindex;

final class Handler implements CommandHandlerInterface
{
    private CommandBusInterface $commandBus;
    private LoggerInterface $parseSuccessLogger;
    private LoggerInterface $parseErrorsLogger;

    public function __construct(
        CommandBusInterface $commandBus,
        LoggerInterface $parseSuccessLogger,
        LoggerInterface $parseErrorsLogger
    ) {
        $this->commandBus = $commandBus;
        $this->parseSuccessLogger = $parseSuccessLogger;
        $this->parseErrorsLogger = $parseErrorsLogger;
    }

    public function __invoke(Command $command): bool
    {
        try {
            $data = $this->parse($command->getTagXml());
            $this->parseSuccessLogger->info($command->getFileToken() . ' ; ' . $command->getTagXml());

            $this->commandBus->dispatch(new SaveCommand($command->getFileToken(), $data));
            return true;
        } catch (Exception $e) {
            $this->parseErrorsLogger->info($command->getFileToken() . ' ; ' . $command->getTagXml() . ' ; ' . $e->getMessage());
            return false;
        }
    }

    private function parse(string $tagXml): array
    {
        $xmlElement = simplexml_load_string($tagXml);
        if (empty($xmlElement)) {
            throw new DomainException('Tag could not be parsed');
        }

        $xmlData = (array)$xmlElement;
        if (empty($xmlData['@attributes'])) {
            throw new DomainException('Failed to get tag attribute values');
        }

        $xmlAttributes = (array)$xmlData['@attributes'];

        return reindex(static fn ($value, $key) => strtolower($key), $xmlAttributes);
    }
}
