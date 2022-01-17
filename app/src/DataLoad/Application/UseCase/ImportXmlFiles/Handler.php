<?php

declare(strict_types=1);

namespace App\DataLoad\Application\UseCase\ImportXmlFiles;

use App\DataLoad\Application\UseCase\FindXmlFile\Query as FindFileQuery;
use App\DataLoad\Application\UseCase\FindXmlFile\Response as FindFileResponse;
use App\DataLoad\Application\UseCase\SplitXmlFile\Command as SplitFileCommand;
use App\Shared\Domain\Bus\Command\CommandBusInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\Bus\Query\QueryBusInterface;

class Handler implements CommandHandlerInterface
{
    private CommandBusInterface $commandBus;
    private QueryBusInterface $queryBus;

    public function __construct(
        CommandBusInterface $commandBus,
        QueryBusInterface $queryBus,
    ) {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    public function __invoke(Command $command)
    {
        $tokens = $command->getTokens();
        $versionId = $command->getVersionId();
        foreach ($tokens as $token) {
            /** @var FindFileResponse $response */
            $response = $this->queryBus->ask(new FindFileQuery($versionId, $token));
            foreach ($response->getAll() as $file) {
                $this->commandBus->dispatch(
                    new SplitFileCommand($versionId, $file->getPath(), $file->getToken(), $file->getTagName())
                );
            }
        }
    }
}
