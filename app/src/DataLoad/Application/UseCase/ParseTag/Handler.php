<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\ParseTag;

use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Infrastructure\Bus\Command\CommandBus;
use App\DataLoad\Application\UseCase\SaveTag\Command as SaveCommand;
use DomainException;

class Handler implements CommandHandlerInterface
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(Command $command): void
    {
        $data = $this->parse($command->getTagXml());

        $this->commandBus->dispatch(new SaveCommand($command->getFileToken(), $data));         
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

        return (array)$xmlData['@attributes'];
    }
}
